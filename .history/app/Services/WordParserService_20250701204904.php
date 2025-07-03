<?php

namespace App\Services;

use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class WordParserService
{
    public function parse(string $filePath): array
    {
        try {
            $phpWord = IOFactory::load($filePath);
        } catch (PhpWordException $e) {
            // Ném lại lỗi với thông báo rõ ràng hơn
            // Lỗi "archive failed" sẽ được bắt ở đây
            throw new \Exception("Không thể đọc file Word. File có thể bị hỏng, có mật khẩu hoặc không tương thích. Lỗi gốc: " . $e->getMessage(), $e->getCode(), $e);
        }
        
        $textContent = '';

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                // Sử dụng getElements() thay vì getText() trực tiếp trên element
                if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    foreach ($element->getElements() as $textElement) {
                         if (method_exists($textElement, 'getText')) {
                             $textContent .= $textElement->getText();
                         }
                    }
                    $textContent .= "\n"; // Thêm xuống dòng sau mỗi TextRun
                } elseif (method_exists($element, 'getText')) {
                     $textContent .= $element->getText() . "\n";
                }
            }
        }
        
        return $this->extractData($textContent);
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
