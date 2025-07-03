<?php

namespace App\Services;

use thiagoalessio\TesseractOCR\TesseractOCR;

class ReportParserService
{
    public function parse($filePath)
    {
        $text = (new TesseractOCR($filePath))->lang('vie')->run();

        preg_match('/Lớp:\s*(\S+)/', $text, $classMatch);
        preg_match('/Lần báo cáo:\s*(\d+)/', $text, $reportRoundMatch);
        preg_match('/Học kỳ:\s*(\d+)/', $text, $semesterMatch);
        preg_match('/Ngày:\s*(\d{2}\/\d{2}\/\d{4})/', $text, $dateMatch);
        preg_match('/Giờ:\s*([\d:]+)\s*–\s*([\d:]+)/u', $text, $timeMatch);
        preg_match('/Địa điểm:\s*(.+)/', $text, $locationMatch);
        preg_match('/(\d+)\s*Nhóm/', $text, $groupMatch);

        preg_match_all('/\d+\s+([^\n]+)\s+(Giáo viên hướng dẫn|Giáo viên phản biện)/u', $text, $teachers, PREG_SET_ORDER);

        $parsedTeachers = [];
        foreach ($teachers as $entry) {
            $parsedTeachers[] = [
                'name' => trim($entry[1]),
                'role' => trim($entry[2])
            ];
        }

        return [[
            'class_code' => $classMatch[1] ?? '',
            'report_round' => $reportRoundMatch[1] ?? '',
            'semester' => $semesterMatch[1] ?? '',
            'date' => $dateMatch[1] ?? '',
            'start_time' => $timeMatch[1] ?? '',
            'end_time' => $timeMatch[2] ?? '',
            'location' => $locationMatch[1] ?? '',
            'num_groups' => (int) ($groupMatch[1] ?? 1),
            'teachers' => $parsedTeachers,
        ]];
    }
}
