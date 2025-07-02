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

        $allText = '';
        $allCells = [];

        foreach ($phpWord->getSections() as $section) {
            $this->extractAllText($section->getElements(), $allText, $allCells);
        }

        // Log toàn bộ text để debug
        \Log::info('=== RAW TEXT CONTENT ===', ['content' => $allText]);
        \Log::info('=== ALL CELLS ===', ['cells' => $allCells]);

        return $this->extractData($allText, $allCells);
    }

    private function extractAllText($elements, &$allText, &$allCells)
    {
        foreach ($elements as $element) {
            if ($element instanceof Text) {
                $text = $element->getText();
                $allText .= $text . ' ';
            } elseif ($element instanceof TextRun) {
                foreach ($element->getElements() as $childElement) {
                    if ($childElement instanceof Text) {
                        $text = $childElement->getText();
                        $allText .= $text . ' ';
                    }
                }
            } elseif ($element instanceof Table) {
                $this->extractTableText($element, $allText, $allCells);
            }
        }
    }

    private function extractTableText(Table $table, &$allText, &$allCells)
    {
        foreach ($table->getRows() as $rowIndex => $row) {
            foreach ($row->getCells() as $colIndex => $cell) {
                $cellText = '';
                
                // Lấy text từ cell, bao gồm cả table lồng bên trong
                $this->extractAllText($cell->getElements(), $cellText, $allCells);
                
                $cellText = trim($cellText);
                if (!empty($cellText)) {
                    $allCells[] = $cellText;
                    $allText .= $cellText . ' ';
                }
            }
        }
    }

    private function extractData(string $allText, array $allCells): array
    {
        $data = [];
        
        // Kết hợp cả text chính và cells để tìm kiếm
        $searchText = $allText . ' ' . implode(' ', $allCells);
        
        \Log::info('=== SEARCH TEXT ===', ['text' => $searchText]);

        // 1. Tìm thông tin lớp
        $classId = $this->findClassId($searchText);
        \Log::info('Found Class ID:', ['class_id' => $classId]);

        // 2. Tìm thông tin ngày, giờ, địa điểm
        $dateTime = $this->findDateTime($searchText);
        \Log::info('Found DateTime:', $dateTime);

        // 3. Tìm thông tin giáo viên
        $teachers = $this->findTeachers($searchText, $allCells);
        \Log::info('Found Teachers:', $teachers);

        // 4. Tìm số nhóm
        $groupCount = $this->findGroupCount($searchText);
        \Log::info('Found Group Count:', ['count' => $groupCount]);

        // Tạo dữ liệu nếu có đủ thông tin cơ bản
        if ($classId && $dateTime['date'] && $dateTime['time_start'] && $dateTime['time_end'] && $dateTime['location']) {
            $recordCount = max($groupCount, 1);
            
            for ($i = 1; $i <= $recordCount; $i++) {
                $data[] = [
                    'class_id' => $classId,
                    'report_name' => $recordCount > 1 ? "Nhóm {$i}" : "Báo cáo đồ án",
                    'instructor_id' => $this->findMaGV($teachers['instructor']),
                    'reviewer_id' => $this->findMaGV($teachers['reviewer']),
                    'report_date' => $dateTime['date'],
                    'report_time_start' => $dateTime['time_start'],
                    'report_time_end' => $dateTime['time_end'],
                    'location' => $dateTime['location'],
                ];
            }
        }

        \Log::info('Final extracted data:', ['count' => count($data), 'data' => $data]);

        return $data;
    }

    private function findClassId(string $text): ?string
    {
        // Tìm pattern CP24Y0G05 hoặc tương tự
        $patterns = [
            '/Lớp:\s*([A-Z0-9]+)/ui',
            '/(CP\d{2}[A-Z]\d{1}[A-Z]\d{2})/ui',
            '/([A-Z]{2}\d{2}[A-Z]\d{1}[A-Z]\d{2})/ui'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                return trim($matches[1]);
            }
        }

        return null;
    }

    private function findDateTime(string $text): array
    {
        $result = [
            'date' => null,
            'time_start' => null,
            'time_end' => null,
            'location' => null
        ];

        // Tìm ngày với nhiều pattern
        $datePatterns = [
            '/Ngày:\s*(\d{1,2}\/\d{1,2}\/\d{4})/ui',
            '/(\d{1,2}\/\d{1,2}\/\d{4})/u',
            '/(\d{1,2}-\d{1,2}-\d{4})/u'
        ];

        foreach ($datePatterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                try {
                    // Thử parse với format d/m/Y
                    $date = Carbon::createFromFormat('d/m/Y', $matches[1]);
                    $result['date'] = $date->format('Y-m-d');
                    break;
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        // Tìm giờ
        $timePatterns = [
            '/Giờ:\s*(\d{1,2}:\d{2})\s*[-–—]\s*(\d{1,2}:\d{2})/ui',
            '/(\d{1,2}:\d{2})\s*[-–—]\s*(\d{1,2}:\d{2})/u',
            '/(\d{1,2}):\s*(\d{2})\s*[-–—]\s*(\d{1,2}):\s*(\d{2})/u'
        ];

        foreach ($timePatterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                if (count($matches) >= 3) {
                    $result['time_start'] = $matches[1];
                    $result['time_end'] = $matches[2];
                    break;
                } elseif (count($matches) >= 5) {
                    $result['time_start'] = $matches[1] . ':' . $matches[2];
                    $result['time_end'] = $matches[3] . ':' . $matches[4];
                    break;
                }
            }
        }

        // Tìm địa điểm
        $locationPatterns = [
            '/Địa\s*điểm:\s*([^\n\r]+)/ui',
            '/Phòng\s*([A-Z0-9\s]+)/ui',
            '/(Lý\s*thuyết\s*\d+)/ui'
        ];

        foreach ($locationPatterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $result['location'] = trim($matches[1]);
                break;
            }
        }

        return $result;
    }

    private function findTeachers(string $text, array $cells): array
    {
        $result = [
            'instructor' => null,
            'reviewer' => null
        ];

        // Tìm trong từng cell để có thể tìm được tên chính xác
        foreach ($cells as $cell) {
            $cell = trim($cell);
            
            // Tìm giáo viên hướng dẫn
            if (preg_match('/(.+?)\s+Giáo\s*viên\s*hướng\s*dẫn/ui', $cell, $matches)) {
                $result['instructor'] = trim($matches[1]);
            }
            
            // Tìm giáo viên phản biện
            if (preg_match('/(.+?)\s+Giáo\s*viên\s*phản\s*biện/ui', $cell, $matches)) {
                $result['reviewer'] = trim($matches[1]);
            }
        }

        // Nếu không tìm được, thử tìm từ text tổng thể
        if (!$result['instructor'] || !$result['reviewer']) {
            // Tìm pattern: Tên GV + tab/space + nhiệm vụ
            $lines = explode("\n", $text);
            foreach ($lines as $line) {
                if (preg_match('/(.+?)\s+Giáo\s*viên\s*hướng\s*dẫn/ui', $line, $matches)) {
                    $result['instructor'] = trim($matches[1]);
                }
                if (preg_match('/(.+?)\s+Giáo\s*viên\s*phản\s*biện/ui', $line, $matches)) {
                    $result['reviewer'] = trim($matches[1]);
                }
            }
        }

        return $result;
    }

    private function findGroupCount(string $text): int
    {
        // Tìm số nhóm
        $patterns = [
            '/(\d+)\s*Nhóm/ui',
            '/(\d+)\s*nhóm/ui',
            '/Nhóm\s*(\d+)/ui'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                return (int)$matches[1];
            }
        }

        return 1; // Mặc định 1 nhóm
    }

    private function findMaGV(?string $hoTen): ?string
    {
        if (empty($hoTen)) {
            return null;
        }

        // Làm sạch tên
        $hoTen = preg_replace('/[^\p{L}\s]/u', '', $hoTen);
        $hoTen = preg_replace('/\s+/', ' ', trim($hoTen));

        if (empty($hoTen)) {
            return null;
        }

        $gv = \App\Models\GiaoVien::where('HoTenGV', 'like', '%' . $hoTen . '%')->first();
        return $gv?->MaGV;
    }
}