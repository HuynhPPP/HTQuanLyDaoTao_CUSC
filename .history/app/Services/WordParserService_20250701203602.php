<?php

namespace App\Services;

use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Str;
use Carbon\Carbon;

// ...
class WordParserService
{
    public function parse(string $filePath): array
    {
        $phpWord = IOFactory::load($filePath);
        $extractedData = []; // Mảng để lưu trữ dữ liệu đã trích xuất

        $class_name = '';
        $report_date = '';
        $report_time_range = '';
        $location = '';
        $instructor_name_from_table = '';
        $reviewer_name_from_table = '';

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    foreach ($element->getElements() as $textElement) {
                        if ($textElement instanceof \PhpOffice\PhpWord\Element\Text) {
                            $text = $textElement->getText();
                            if (Str::contains($text, 'Lớp:')) {
                                $class_name = trim(Str::after($text, 'Lớp:'));
                            }
                            // Các dòng khác như Ngày, Giờ, Địa điểm có thể nằm trong TextRun
                            // Dựa trên file bạn đã gửi, "Ngày:", "Giờ:", "Địa điểm:" nằm trong cùng một dòng.
                            // Cần một regex hoặc logic phức tạp hơn để phân tách chúng.
                            // Đoạn này trong extractData của bạn đã khá tốt, nhưng cần được tích hợp vào đây.
                        }
                    }
                } elseif ($element instanceof \PhpOffice\PhpWord\Element\Table) {
                    // Logic cụ thể để duyệt bảng và trích xuất dữ liệu
                    // Dựa vào cấu trúc bảng trong file BM06.63-Bang phan cong cham do an-CP24Y0G05.doc 
                    foreach ($element->getRows() as $rowIndex => $row) {
                        // Bảng THÀNH PHẦN
                        if ($rowIndex === 3 && $row->getCells()[1]->getText() === 'Nguyễn Việt Nga' && $row->getCells()[2]->getText() === 'Giáo viên hướng dẫn') { [cite: 2]
                            $instructor_name_from_table = $row->getCells()[1]->getText(); [cite: 2]
                        }
                        if ($rowIndex === 4 && $row->getCells()[1]->getText() === 'Nguyễn Trung Kiên' && $row->getCells()[2]->getText() === 'Giáo viên phản biện') { [cite: 2]
                            $reviewer_name_from_table = $row->getCells()[1]->getText(); [cite: 2]
                        }
                        // Bảng LỊCH BÁO CÁO (nếu có nhiều dòng đồ án) 
                        // Dòng tiêu đề: STT ĐỒ ÁN GIÁO VIÊN HƯỚNG DẪN GIÁO VIÊN PHẢN BIỆN 
                        // Dòng dữ liệu: 1 05 Nhóm Lớp: CP24Y0G05 Nguyễn Việt Nga Nguyễn Trung Kiên 
                        if ($rowIndex > 5 && count($row->getCells()) >= 4) { // Bỏ qua tiêu đề bảng 
                            $stt = trim($row->getCells()[0]->getText()); [cite: 2]
                            $report_name = trim($row->getCells()[1]->getText()); [cite: 2]
                            $gvhd = trim($row->getCells()[2]->getText()); [cite: 2]
                            $gvpb = trim($row->getCells()[3]->getText()); [cite: 2]

                            // Lấy ngày, giờ, địa điểm từ dòng nằm ngoài bảng (nếu chúng cố định)
                            // Hoặc từ dòng chứa "Ngày: 17/01/2025 Giờ: 7:00 – 12:00 Địa điểm: Lý thuyết 04" 
                            preg_match('/Ngày:\s*(\d{2}\/\d{2}\/\d{4})\s*Giờ:\s*(\d{1,2}:\d{2})\s*[–-]\s*(\d{1,2}:\d{2})\s*Địa điểm:\s*(.+)/', $phpWord->getText(), $matchesNgayGioDiaDiem);
                            if (!empty($matchesNgayGioDiaDiem)) {
                                $report_date = Carbon::createFromFormat('d/m/Y', $matchesNgayGioDiaDiem[1])->format('Y-m-d');
                                $report_time_start = $matchesNgayGioDiaDiem[2];
                                $report_time_end = $matchesNgayGioDiaDiem[3];
                                $location = $matchesNgayGioDiaDiem[4];
                            }

                            $extractedData[] = [
                                'class_id' => $class_name, // Lấy từ phần text ở trên
                                'report_name' => $report_name,
                                'instructor_id' => $this->findMaGV($gvhd),
                                'reviewer_id' => $this->findMaGV($gvpb),
                                'report_date' => $report_date,
                                'report_time_start' => $report_time_start,
                                'report_time_end' => $report_time_end,
                                'location' => $location,
                            ];
                        }
                    }
                }
            }
        }
        return $extractedData;
    }

    private function findMaGV(string $hoTen): ?string
    {
        // Cải thiện regex để tìm tên, loại bỏ các khoảng trắng thừa
        $hoTenClean = trim(preg_replace('/\s+/', ' ', $hoTen));
        $gv = \App\Models\giaovien::where('HoTenGV', $hoTenClean)->first(); // Tìm kiếm chính xác hoặc dùng like nếu cần
        return $gv?->MaGV;
    }
    // ... (phần extractData cũ có thể bỏ đi hoặc điều chỉnh để nó gọi từ đây)
}
