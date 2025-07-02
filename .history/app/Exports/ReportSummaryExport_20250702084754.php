<?php 

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class ReportSummaryExport implements FromArray
{
    protected $summary;

    public function __construct(array $summary)
    {
        $this->summary = $summary;
    }

    public function array(): array
    {
        return array_merge([
            ['HỌ VÀ TÊN', 'CÔNG VIỆC', 'SỐ GIỜ', 'GHI CHÚ']
        ], $this->formatRows());
    }

    protected function formatRows(): array
    {
        $rows = [];

        foreach ($this->summary as $entry) {
            if ($entry['huong_dan'] > 0) {
                $rows[] = [$entry['name'], 'Giáo viên hướng dẫn', $entry['huong_dan'], 'Năm ' . $entry['semester']];
            }
            if ($entry['phan_bien'] > 0) {
                $rows[] = [$entry['name'], 'Giáo viên phản biện', $entry['phan_bien'], 'Năm ' . $entry['semester']];
            }
            $rows[] = [$entry['name'], 'Chấm đồ án', $entry['tong_cham'], ''];
        }

        return $rows;
    }
}
