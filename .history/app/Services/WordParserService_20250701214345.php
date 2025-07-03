<?php

namespace App\Services;

use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PhpOffice\PhpWord\Exception\Exception as PhpWordException;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Table;

class WordParserService
{
    public function parse(string $filePath): array
    {
        try {
            $phpWord = IOFactory::load($filePath);
        } catch (PhpWordException $e) {
            throw new \Exception("Không thể đọc file Word. File có thể bị hỏng, có mật khẩu hoặc không tương thích. Lỗi gốc: " . $e->getMessage(), $e->getCode(), $e);
        }

        // Thu thập tất cả text từ document
        $allTexts = [];
        $this->extractAllTexts($phpWord, $allTexts);
        
        // Log tất cả text tìm được
        \Log::info('=== ALL EXTRACTED TEXTS ===');
        foreach ($allTexts as $index => $text) {
            \Log::info("Text {$index}: " . json_encode($text));
        }

        // Ghép tất cả text lại để tìm kiếm
        $fullText = implode("\n", $allTexts);
        \Log::info('=== FULL COMBINED TEXT ===', ['text' => $fullText]);

        return $this->parseData($fullText, $allTexts);
    }

    private function extractAllTexts($phpWord, &$allTexts)
    {
        foreach ($phpWord->getSections() as $section) {
            $this->processElements($section->getElements(), $allTexts);
        }
    }

    private function processElements($elements, &$allTexts, $depth = 0)
    {
        foreach ($elements as $element) {
            $className = get_class($element);
            $prefix = str_repeat("  ", $depth);
            
            if ($element instanceof Text) {
                $text = trim($element->getText());
                if (!empty($text)) {
                    $allTexts[] = $text;
                    \Log::info("{$prefix}Text: {$text}");
                }
            } 
            elseif ($element instanceof TextRun) {
                $textRunContent = '';
                foreach ($element->getElements() as $childElement) {
                    if ($childElement instanceof Text) {
                        $textRunContent .= $childElement->getText();
                    }
                }
                $textRunContent = trim($textRunContent);
                if (!empty($textRunContent)) {
                    $allTexts[] = $textRunContent;
                    \Log::info("{$prefix}TextRun: {$textRunContent}");
                }
            }
            elseif ($element instanceof Table) {
                \Log::info("{$prefix}Table found:");
                $this->processTable($element, $allTexts, $depth + 1);
            }
            else {
                \Log::info("{$prefix}Other element: {$className}");
                // Nếu element có getElements(), xử lý đệ quy
                if (method_exists($element, 'getElements')) {
                    $this->processElements($element->getElements(), $allTexts, $depth + 1);
                }
            }
        }
    }

    private function processTable($table, &$allTexts, $depth = 0)
    {
        $prefix = str_repeat("  ", $depth);
        
        foreach ($table->getRows() as $rowIndex => $row) {
            \Log::info("{$prefix}Row {$rowIndex}:");
            foreach ($row->getCells() as $cellIndex => $cell) {
                $cellTexts = [];
                $this->processCellElements($cell->getElements(), $cellTexts, $depth + 1);
                
                $cellContent = trim(implode(' ', $cellTexts));
                if (!empty($cellContent)) {
                    $allTexts[] = $cellContent;
                    \Log::info("{$prefix}  Cell[{$cellIndex}]: {$cellContent}");
                }
            }
        }
    }

    private function processCellElements($elements, &$cellTexts, $depth = 0)
    {
        foreach ($elements as $element) {
            if ($element instanceof Text) {
                $text = trim($element->getText());
                if (!empty($text)) {
                    $cellTexts[] = $text;
                }
            } 
            elseif ($element instanceof TextRun) {
                $textRunContent = '';
                foreach ($element->getElements() as $childElement) {
                    if ($childElement instanceof Text) {
                        $textRunContent .= $childElement->getText();
                    }
                }
                $textRunContent = trim($textRunContent);
                if (!empty($textRunContent)) {
                    $cellTexts[] = $textRunContent;
                }
            }
            elseif ($element instanceof Table) {
                // Xử lý table lồng nhau
                $this->processTable($element, $cellTexts, $depth + 1);
            }
            else {
                // Xử lý các element khác
                if (method_exists($element, 'getElements')) {
                    $this->processCellElements($element->getElements(), $cellTexts, $depth + 1);
                }
            }
        }
    }

    private function parseData(string $fullText, array $allTexts): array
    {
        $result = [];
        
        // 1. Tìm lớp
        $classId = $this->findInTexts('class', $allTexts, $fullText);
        \Log::info('=== FOUND CLASS ===', ['class_id' => $classId]);

        // 2. Tìm ngày
        $date = $this->findInTexts('date', $allTexts, $fullText);
        \Log::info('=== FOUND DATE ===', ['date' => $date]);

        // 3. Tìm giờ
        $times = $this->findInTexts('time', $allTexts, $fullText);
        \Log::info('=== FOUND TIMES ===', $times);

        // 4. Tìm địa điểm
        $location = $this->findInTexts('location', $allTexts, $fullText);
        \Log::info('=== FOUND LOCATION ===', ['location' => $location]);

        // 5. Tìm giáo viên
        $teachers = $this->findInTexts('teachers', $allTexts, $fullText);
        \Log::info('=== FOUND TEACHERS ===', $teachers);

        // 6. Tìm số nhóm
        $groupCount = $this->findInTexts('groups', $allTexts, $fullText);
        \Log::info('=== FOUND GROUPS ===', ['count' => $groupCount]);

        // Tạo kết quả
        if ($classId && $date && $times['start'] && $times['end'] && $location) {
            $count = max($groupCount, 1);
            for ($i = 1; $i <= $count; $i++) {
                $result[] = [
                    'class_id' => $classId,
                    'report_name' => $count > 1 ? "Nhóm {$i}" : "Báo cáo đồ án",
                    'instructor_id' => $this->findMaGV($teachers['instructor']),
                    'reviewer_id' => $this->findMaGV($teachers['reviewer']),
                    'report_date' => $date,
                    'report_time_start' => $times['start'],
                    'report_time_end' => $times['end'],
                    'location' => $location,
                ];
            }
        }

        \Log::info('=== FINAL RESULT ===', ['count' => count($result), 'data' => $result]);
        return $result;
    }

    private function findInTexts(string $type, array $allTexts, string $fullText)
    {
        switch ($type) {
            case 'class':
                // Tìm mã lớp
                foreach ($allTexts as $text) {
                    if (preg_match('/(CP\d{2}[A-Z]\d{1}[A-Z]\d{2})/i', $text, $matches)) {
                        return $matches[1];
                    }
                    if (preg_match('/Lớp:\s*([A-Z0-9]+)/i', $text, $matches)) {
                        return trim($matches[1]);
                    }
                }
                return null;

            case 'date':
                // Tìm ngày
                foreach ($allTexts as $text) {
                    if (preg_match('/(\d{1,2}\/\d{1,2}\/\d{4})/', $text, $matches)) {
                        try {
                            return Carbon::createFromFormat('d/m/Y', $matches[1])->format('Y-m-d');
                        } catch (\Exception $e) {
                            continue;
                        }
                    }
                }
                return null;

            case 'time':
                // Tìm giờ
                foreach ($allTexts as $text) {
                    if (preg_match('/(\d{1,2}:\d{2})\s*[-–]\s*(\d{1,2}:\d{2})/', $text, $matches)) {
                        return ['start' => $matches[1], 'end' => $matches[2]];
                    }
                }
                return ['start' => null, 'end' => null];

            case 'location':
                // Tìm địa điểm
                foreach ($allTexts as $text) {
                    if (preg_match('/Địa\s*điểm:\s*(.+)$/i', $text, $matches)) {
                        return trim($matches[1]);
                    }
                    if (preg_match('/(Lý\s*thuyết\s*\d+)/i', $text, $matches)) {
                        return trim($matches[1]);
                    }
                }
                return null;

            case 'teachers':
                // Tìm giáo viên
                $instructor = null;
                $reviewer = null;
                
                foreach ($allTexts as $text) {
                    if (preg_match('/(.+?)\s+Giáo\s*viên\s*hướng\s*dẫn/i', $text, $matches)) {
                        $instructor = trim($matches[1]);
                    }
                    if (preg_match('/(.+?)\s+Giáo\s*viên\s*phản\s*biện/i', $text, $matches)) {
                        $reviewer = trim($matches[1]);
                    }
                }
                
                return ['instructor' => $instructor, 'reviewer' => $reviewer];

            case 'groups':
                // Tìm số nhóm
                foreach ($allTexts as $text) {
                    if (preg_match('/(\d+)\s*Nhóm/i', $text, $matches)) {
                        return (int)$matches[1];
                    }
                }
                return 1;

            default:
                return null;
        }
    }

    private function findMaGV(?string $hoTen): ?string
    {
        if (empty($hoTen)) {
            return null;
        }

        $hoTen = preg_replace('/[^\p{L}\s]/u', '', $hoTen);
        $hoTen = preg_replace('/\s+/', ' ', trim($hoTen));

        if (empty($hoTen)) {
            return null;
        }

        $gv = \App\Models\GiaoVien::where('HoTenGV', 'like', '%' . $hoTen . '%')->first();
        return $gv?->MaGV;
    }
}