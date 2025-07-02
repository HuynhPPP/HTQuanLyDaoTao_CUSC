<?php

namespace App\Services;

use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PhpOffice\PhpWord\Exception\Exception as PhpWordException;

class WordParserService
{
    public function parse(string $filePath): array
    {
        try {
            $phpWord = IOFactory::load($filePath);
        } catch (PhpWordException $e) {
            throw new \Exception("Không thể đọc file Word. File có thể bị hỏng, có mật khẩu hoặc không tương thích. Lỗi gốc: " . $e->getMessage(), $e->getCode(), $e);
        }

        $textContent = '';

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if ($element instanceof Text) { // Đây là loại element có getText()
                    $textContent .= $element->getText() . "\n";
                } elseif ($element instanceof TextRun) {
                    // Nếu element là một TextRun, duyệt qua các element con của nó
                    foreach ($element->getElements() as $childElement) {
                        if ($childElement instanceof Text) {
                            $textContent .= $childElement->getText();
                        }
                        // Nếu có các loại element khác trong TextRun (ví dụ: Link, Field, ...),
                        // bạn cần xử lý chúng ở đây nếu muốn đưa vào textContent
                    }
                    $textContent .= "\n"; // Xuống dòng sau mỗi TextRun lớn
                }
                // Thêm logic xử lý các loại element khác như Table nếu cần
                elseif ($element instanceof Table) {
                    foreach ($element->getRows() as $row) {
                        foreach ($row->getCells() as $cell) {
                            // Để lấy text từ cell, bạn cũng phải duyệt qua các elements bên trong cell
                            foreach ($cell->getElements() as $cellElement) {
                                if ($cellElement instanceof TextRun) {
                                    foreach ($cellElement->getElements() as $cellChild) {
                                        if ($cellChild instanceof Text) {
                                            $textContent .= $cellChild->getText();
                                        }
                                    }
                                } elseif ($cellElement instanceof Text) {
                                    $textContent .= $cellElement->getText();
                                }
                                // Thêm các loại element khác nếu có trong cell
                            }
                            $textContent .= "\t"; // Dùng tab để phân biệt cột trong bảng
                        }
                        $textContent .= "\n"; // Xuống dòng sau mỗi hàng
                    }
                    $textContent .= "\n"; // Thêm một dòng trống sau mỗi bảng
                }
                // Bạn có thể thêm các elseif khác cho Image, Line, ... nếu bạn muốn xử lý chúng.
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
