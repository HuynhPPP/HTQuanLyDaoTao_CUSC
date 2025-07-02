<?php

namespace App\Services;

class ReportSummaryService
{
    public function summarize(array $reportEntries): array
    {
        $summary = [];

        foreach ($reportEntries as $entry) {
            $numGroups = $entry['num_groups'];
            $factor = ($entry['semester'] == 1) ? 1.5 : 2.0;

            foreach ($entry['teachers'] as $teacher) {
                $name = $teacher['name'];
                $role = $teacher['role'];
                $hours = $numGroups * $factor;

                if (!isset($summary[$name])) {
                    $summary[$name] = [
                        'name' => $name,
                        'huong_dan' => 0,
                        'phan_bien' => 0,
                        'tong_cham' => 0,
                        'semester' => $entry['semester'],
                    ];
                }

                if ($role === 'Giáo viên hướng dẫn') {
                    $summary[$name]['huong_dan'] += $hours;
                } elseif ($role === 'Giáo viên phản biện') {
                    $summary[$name]['phan_bien'] += $hours;
                }

                $summary[$name]['tong_cham'] += $hours;
            }
        }

        return array_values($summary);
    }
}
