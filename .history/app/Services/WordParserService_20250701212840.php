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

        $textContent = '';
        $tableData = [];

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if ($element instanceof Text) {
                    $textContent .= $element->getText() . "\n";
                } elseif ($element instanceof TextRun) {
                    foreach ($element->getElements() as $childElement) {
                        if ($childElement instanceof Text) {
                            $textContent .= $childElement->getText();
                        }
                    }
                    $textContent .= "\n";
                } elseif ($element instanceof Table) {
                    // Xử lý bảng riêng biệt
                    $tableContent = $this->parseTable($element);
                    $tableData[] = $tableContent;
                    $textContent .= $tableContent . "\n";
                }
            }
        }

        // Log để debug
        \Log::info('Parsed text content:', ['content' => $textContent]);
        \Log::info('Table data:', $tableData);

        return $this->extractData($textContent, $tableData);
    }

    private function parseTable(Table $table): string
    {
        $tableText = "";
        foreach ($table->getRows() as $row) {
            $rowText = [];
            foreach ($row->getCells() as $cell) {
                $cellText = "";
                foreach ($cell->getElements() as $cellElement) {
                    if ($cellElement instanceof TextRun) {
                        foreach ($cellElement->getElements() as $cellChild) {
                            if ($cellChild instanceof Text) {
                                $cellText .= $cellChild->getText();
                            }
                        }
                    } elseif ($cellElement instanceof Text) {
                        $cellText .= $cellElement->getText();
                    }
                }
                $rowText[] = trim($cellText);
            }
            $tableText .= implode("\t", $rowText) . "\n";
        }
        return $tableText;
    }

    private function extractData(string $text, array $tableData = []): array
    {
        $lines = explode("\n", $text);
        $data = [];
        $classId = null;
        $currentDay = null;
        $timeStart = null;
        $timeEnd = null;
        $location = null;
        $gvhd = null;
        $gvpb = null;

        // Tìm thông tin cơ bản
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Tìm lớp - có thể ở nhiều định dạng
            if (preg_match('/Lớp:\s*([A-Z0-9]+)/i', $line, $matches)) {
                $classId = $matches[1];
            }
            
            // Tìm trong dòng có chứa CP24Y0G05
            if (preg_match('/(CP\d{2}[A-Z]\d{1}[A-Z]\d{2})/i', $line, $matches)) {
                $classId = $matches[1];
            }
        }

        // Tìm thông tin ngày giờ địa điểm - pattern mới
        $fullText = implode(' ', $lines);
        
        // Tìm ngày
        if (preg_match('/Ngày:\s*(\d{1,2}\/\d{1,2}\/\d{4})/i', $fullText, $matches)) {
            try {
                $currentDay = Carbon::createFromFormat('d/m/Y', $matches[1])->format('Y-m-d');
            } catch (\Exception $e) {
                $currentDay = null;
            }
        }

        // Tìm giờ - nhiều pattern khác nhau
        if (preg_match('/Giờ:\s*(\d{1,2}:\d{2})\s*[-–]\s*(\d{1,2}:\d{2})/i', $fullText, $matches)) {
            $timeStart = $matches[1];
            $timeEnd = $matches[2];
        } elseif (preg_match('/(\d{1,2}:\d{2})\s*[-–]\s*(\d{1,2}:\d{2})/', $fullText, $matches)) {
            $timeStart = $matches[1];
            $timeEnd = $matches[2];
        }

        // Tìm địa điểm
        if (preg_match('/Địa\s*điểm:\s*([^\n\t]+)/i', $fullText, $matches)) {
            $location = trim($matches[1]);
        }

        // Tìm giáo viên từ bảng thành phần
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Tìm giáo viên hướng dẫn
            if (preg_match('/(.+?)\s+Giáo viên hướng dẫn/i', $line, $matches)) {
                $gvhd = trim($matches[1]);
            }
            
            // Tìm giáo viên phản biện
            if (preg_match('/(.+?)\s+Giáo viên phản biện/i', $line, $matches)) {
                $gvpb = trim($matches[1]);
            }
        }

        // Tìm thông tin nhóm và đồ án
        $groupCount = 0;
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Tìm số nhóm
            if (preg_match('/(\d+)\s*Nhóm/i', $line, $matches)) {
                $groupCount = (int)$matches[1];
                break;
            }
        }

        // Nếu tìm thấy thông tin cơ bản, tạo record
        if ($classId && $currentDay && $timeStart && $timeEnd && $location) {
            // Tạo record cho số nhóm tìm được, hoặc ít nhất 1 record
            $recordCount = max($groupCount, 1);
            
            for ($i = 1; $i <= $recordCount; $i++) {
                $data[] = [
                    'class_id' => $classId,
                    'report_name' => $recordCount > 1 ? "Nhóm {$i}" : "Báo cáo đồ án",
                    'instructor_id' => $this->findMaGV($gvhd),
                    'reviewer_id' => $this->findMaGV($gvpb),
                    'report_date' => $currentDay,
                    'report_time_start' => $timeStart,
                    'report_time_end' => $timeEnd,
                    'location' => $location,
                ];
            }
        }

        // Log để debug
        \Log::info('Extracted data:', [
            'classId' => $classId,
            'currentDay' => $currentDay,
            'timeStart' => $timeStart,
            'timeEnd' => $timeEnd,
            'location' => $location,
            'gvhd' => $gvhd,
            'gvpb' => $gvpb,
            'groupCount' => $groupCount,
            'dataCount' => count($data)
        ]);

        return $data;
    }

    /**
     * Tìm mã giáo viên từ tên
     */
    private function findMaGV(?string $hoTen): ?string
    {
        if (empty($hoTen)) {
            return null;
        }

        // Làm sạch tên (loại bỏ ký tự đặc biệt, khoảng trắng thừa)
        $hoTen = preg_replace('/[^\p{L}\s]/u', '', $hoTen);
        $hoTen = preg_replace('/\s+/', ' ', trim($hoTen));

        if (empty($hoTen)) {
            return null;
        }

        $gv = \App\Models\GiaoVien::where('HoTenGV', 'like', '%' . $hoTen . '%')->first();
        return $gv?->MaGV;
    }
}