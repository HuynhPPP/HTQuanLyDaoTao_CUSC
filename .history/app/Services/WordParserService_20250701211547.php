<?php

namespace App\Services;

use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PhpOffice\PhpWord\Exception\Exception as PhpWordException;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Row;
use PhpOffice\PhpWord\Element\Cell;

class WordParserService
{
    public function parse(string $filePath): array
    {
        try {
            $phpWord = IOFactory::load($filePath);
        } catch (PhpWordException $e) {
            throw new \Exception("Không thể đọc file Word. File có thể bị hỏng, có mật khẩu hoặc không tương thích. Lỗi gốc: " . $e->getMessage(), $e->getCode(), $e);
        }

        $records = [];
        $class_name = null;
        $report_date_str = null;
        $report_time_range_str = null;
        $location = null;

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                // Trích xuất thông tin chung từ TextRun (Lớp, Ngày, Giờ, Địa điểm)
                if ($element instanceof TextRun) {
                    foreach ($element->getElements() as $child) {
                        if ($child instanceof Text) {
                            $text = $child->getText();
                            if (Str::startsWith($text, 'Lớp:')) {
                                $class_name = trim(Str::after($text, 'Lớp:'));
                            }
                            // Dựa trên file mẫu, Ngày, Giờ, Địa điểm nằm trong cùng 1 dòng
                            if (Str::contains($text, 'Ngày:') && Str::contains($text, 'Giờ:') && Str::contains($text, 'Địa điểm:')) {
                                preg_match('/Ngày:\s*(\d{2}\/\d{2}\/\d{4})\s+Giờ:\s*(\d{1,2}:\d{2})\s*–\s*(\d{1,2}:\d{2})\s+Địa điểm:\s*(.+)/', $text, $matches); [cite: 2]
                                if (!empty($matches)) {
                                    $report_date_str = $matches[1]; [cite: 2]
                                    $report_time_range_str = $matches[2] . '–' . $matches[3]; [cite: 2]
                                    $location = $matches[4]; [cite: 2]
                                }
                            }
                        }
                    }
                }
                // Trích xuất thông tin giáo viên và đồ án từ Table
                elseif ($element instanceof Table) {
                    // Lấy tất cả rows của bảng
                    $rows = $element->getRows();

                    // Xác định hàng chứa thông tin phân công đồ án
                    // Dựa vào cấu trúc file BM06.63, hàng dữ liệu bắt đầu từ dòng thứ 6 (index 5)
                    // Hàng tiêu đề: STT ĐỒ ÁN GIÁO VIÊN HƯỚNG DẪN GIÁO VIÊN PHẢN BIỆN
                    // Dòng 1: STT 
                    // Dòng 2: ĐỒ ÁN 
                    // Dòng 3: GIÁO VIÊN HƯỚNG DẪN 
                    // Dòng 4: GIÁO VIÊN PHẢN BIỆN 
                    // Ví dụ: dò tìm hàng chứa "ĐỒ ÁN" để biết đó là hàng tiêu đề
                    $headerRowIndex = -1;
                    foreach ($rows as $idx => $row) {
                        if (count($row->getCells()) > 1 && Str::contains(trim($row->getCells()[1]->getText()), 'ĐỒ ÁN')) { [cite: 2]
                            $headerRowIndex = $idx;
                            break;
                        }
                    }

                    if ($headerRowIndex !== -1) {
                        // Duyệt qua các hàng dữ liệu sau hàng tiêu đề
                        for ($i = $headerRowIndex + 1; $i < count($rows); $i++) {
                            $row = $rows[$i];
                            $cells = $row->getCells();

                            // Kiểm tra số lượng cột để đảm bảo đúng định dạng bảng phân công
                            if (count($cells) >= 4) { [cite: 2]
                                $stt = trim($cells[0]->getText()); [cite: 2]
                                $report_name = trim($cells[1]->getText()); [cite: 2]
                                $gvhd_name = trim($cells[2]->getText()); [cite: 2]
                                $gvpb_name = trim($cells[3]->getText()); [cite: 2]

                                // Chuyển đổi ngày và giờ sang định dạng cần lưu DB
                                $report_date_formatted = null;
                                if ($report_date_str) {
                                    $report_date_formatted = Carbon::createFromFormat('d/m/Y', $report_date_str)->format('Y-m-d');
                                }

                                $time_start = null;
                                $time_end = null;
                                if ($report_time_range_str) {
                                    $time_parts = explode('–', $report_time_range_str);
                                    if (count($time_parts) == 2) {
                                        $time_start = trim($time_parts[0]);
                                        $time_end = trim($time_parts[1]);
                                    }
                                }
                                
                                // Tìm MaLop và MaGV
                                $lop = \App\Models\lophoc::firstOrCreate(['TenLop' => $class_name]);
                                $gvhd = \App\Models\giaovien::firstOrCreate(['HoTenGV' => $gvhd_name]);
                                $gvpb = \App\Models\giaovien::firstOrCreate(['HoTenGV' => $gvpb_name]);
                                
                                $records[] = [
                                    'class_id' => $lop->MaLop,
                                    'report_name' => $report_name,
                                    'instructor_id' => $gvhd->MaGV,
                                    'reviewer_id' => $gvpb->MaGV,
                                    'report_date' => $report_date_formatted,
                                    'report_time_start' => $time_start,
                                    'report_time_end' => $time_end,
                                    'location' => $location,
                                ];
                            }
                        }
                    }
                }
            }
        }
        return $records;
    }

    private function extractData(string $text): array
    {
        $lines = explode("\n", $text);
        $data = [];
        $classId = null;
        $currentDay = null;
        $timeStart = null;
        $timeEnd = null;
        $location = null;

        foreach ($lines as $line) {
            $line = trim($line);

            // Detect Lớp
            if (Str::startsWith($line, 'Lớp:')) {
                $classId = trim(Str::after($line, 'Lớp:'));
            }

            // Detect Ngày báo cáo
            if (Str::contains($line, 'Ngày:') && Str::contains($line, 'Địa điểm')) {
                preg_match('/Ngày:\s*(\d{2}\/\d{2}\/\d{4})/', $line, $ngayMatch);
                preg_match('/(\d{1,2}:\d{2})\s*[-–]\s*(\d{1,2}:\d{2})/', $line, $gioMatch);
                preg_match('/Địa điểm:\s*(.+)$/', $line, $diadiemMatch);

                if ($ngayMatch) {
                    $currentDay = Carbon::createFromFormat('d/m/Y', $ngayMatch[1])->format('Y-m-d');
                }
                if ($gioMatch) {
                    $timeStart = $gioMatch[1];
                    $timeEnd = $gioMatch[2];
                }
                if ($diadiemMatch) {
                    $location = $diadiemMatch[1];
                }
            }

            // Detect dòng phân công GV
            if (preg_match('/^(\d+)\.\s+(.*?)\s{2,}(.*?)\s{2,}(.*?)$/', $line, $matches)) {
                $tenDeTai = $matches[2] ?? null;
                $gvhd = trim($matches[3]);
                $gvpb = trim($matches[4]);

                $data[] = [
                    'class_id' => $classId,
                    'report_name' => $tenDeTai,
                    'instructor_id' => $this->findMaGV($gvhd),
                    'reviewer_id' => $this->findMaGV($gvpb),
                    'report_date' => $currentDay,
                    'report_time_start' => $timeStart,
                    'report_time_end' => $timeEnd,
                    'location' => $location,
                ];
            }
        }

        return $data;
    }

    /**
     * Tìm mã giáo viên từ tên – có thể cập nhật dùng DB thật
     */
    private function findMaGV(string $hoTen): ?string
    {
        $gv = \App\Models\GiaoVien::where('HoTenGV', 'like', '%' . $hoTen . '%')->first();
        return $gv?->MaGV;
    }
}
