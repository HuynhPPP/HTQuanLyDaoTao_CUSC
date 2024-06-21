<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ScheduleExport implements FromCollection, WithHeadings, WithTitle, WithCustomStartCell, WithStyles
{
    protected $tkb;
    protected $chuongtrinh;
    protected $phonglt;
    protected $phongth;
    protected $hocki;
    protected $dsdkmn;
    protected $monhocs;
    protected $subjectOccurrences;
    protected $examDays;

    public function __construct($tkb, $chuongtrinh, $phonglt, $phongth, $dsdkmn, $hocki, $monhocs)
    {
        $this->tkb = $tkb;
        $this->chuongtrinh = $chuongtrinh;
        $this->phonglt = $phonglt;
        $this->phongth = $phongth;
        $this->dsdkmn = $dsdkmn;
        $this->hocki = $hocki;
        $this->monhocs = $monhocs->filter(function($monhoc) {
            return $monhoc->GioTrienKhai > 0;
        });

        $this->subjectOccurrences = [];
        $subjectCount = count($this->monhocs);
        foreach ($this->monhocs as $index => $monhoc) {
            $this->subjectOccurrences[$monhoc->TenMH] = [
                'first' => null,
                'last' => null,
                'remaining' => $monhoc->GioTrienKhai,
                'lastSubject' => ($index === $subjectCount - 1)
            ];
        }

        $this->examDays = [];
    }

    public function collection()
    {
        $data = [];
        $data[] = ['', '', '', '', '', '', '', '', '', ''];
        $startDate = $this->tkb ? Carbon::parse($this->tkb->NgayHoc) : null;
        $totalHours = $this->hocki->TongGioTrienKhai;
    
        // Calculate initial empty days before the start date
        $emptyDays = $this->calculateEmptyDays($startDate);
        $totalHours += $emptyDays * 2;
    
        // Cập nhật lại tổng tuần sau khi cập nhật tổng giờ học
        $this->totalWeeks = ceil($totalHours / 10);
        $weekDays = ['THỨ HAI', 'THỨ BA', 'THỨ TƯ', 'THỨ NĂM', 'THỨ SÁU'];
    
        for ($week = 1; $week <= $this->totalWeeks; $week++) {
            $weekStart = $startDate ? $startDate->copy()->addWeeks($week - 1)->startOfWeek() : null;
            $weekEnd = $weekStart ? $weekStart->copy()->endOfWeek()->subDays(2) : null;
    
            $row = [
                $weekStart ? $weekStart->format('d/m/Y') : '',
                '-',
                $weekEnd ? $weekEnd->format('d/m/Y') : '',
                $week,
                $this->dsdkmn->TenKhungGio
            ];
    
            foreach ($weekDays as $day) {
                $currentDate = $weekStart ? $weekStart->copy()->addDays(array_search($day, $weekDays)) : null;
                $subject = '';
    
                if ($currentDate && $currentDate->gte($startDate)) {
                    if (isset($this->examDays[$currentDate->format('Y-m-d')])) {
                        $subject = $this->examDays[$currentDate->format('Y-m-d')];
                    } else {
                        $subject = $this->getSubjectForDay($currentDate, $totalHours);
                        $this->totalWeeks = ceil($totalHours / 10);
                    }
                }
    
                $row[] = $subject;
            }
    
            $data[] = $row;
        }
    
        $this->totalRows = count($data); // Lưu tổng số hàng vào biến thành viên
    
        return collect($data);
    }
    
    protected function calculateEmptyDays($startDate)
    {
        $weekStartDate = $startDate->copy()->startOfWeek();
        $emptyDays = 0;

        for ($date = $weekStartDate; $date->lt($startDate); $date->addDay()) {
            if ($date->dayOfWeek !== Carbon::SATURDAY && $date->dayOfWeek !== Carbon::SUNDAY) {
                $emptyDays++;
            }
        }

        return $emptyDays;
    }

    protected function getSubjectForDay(&$currentDate, &$totalHours)
    {
        foreach ($this->subjectOccurrences as $subject => &$details) {
            if ($details['remaining'] > 0) {
                if (is_null($details['first'])) {
                    $details['first'] = $currentDate;
                }
                $details['remaining'] -= 2;
                if ($details['remaining'] <= 0) {
                    $details['last'] = $currentDate;

                    if ($details['lastSubject']) {
                        // Calculate exam date for the last subject
                        $examDate = $currentDate->copy()->addWeek()->startOfWeek()->next(Carbon::FRIDAY);
                        
                        // Mark days between the last class day and the exam date as self-study
                        $emptyDays = $currentDate->diffInDays($examDate) - 1;
                        for ($i = 0; $i < $emptyDays; $i++) {
                            $selfStudyDate = $currentDate->copy()->addDays($i + 1);
                            if ($selfStudyDate->dayOfWeek !== Carbon::SATURDAY && $selfStudyDate->dayOfWeek !== Carbon::SUNDAY) {
                                $this->examDays[$selfStudyDate->format('Y-m-d')] = "self-study";
                                $totalHours += 2;
                            }
                        }
                    } else {
                        // Calculate exam date for other subjects
                        $examDate = $this->addDaysSkippingWeekends(clone $currentDate, 5);

                        // Mark the day before the exam as self-study, if applicable
                        if ($examDate->dayOfWeek !== Carbon::MONDAY) {
                            $selfStudyDate = $examDate->copy()->subDay();
                            if ($selfStudyDate->dayOfWeek !== Carbon::SATURDAY && $selfStudyDate->dayOfWeek !== Carbon::SUNDAY) {
                                $this->examDays[$selfStudyDate->format('Y-m-d')] = "self-study";
                                $totalHours += 2;
                            }
                        }
                    }

                    $this->examDays[$examDate->format('Y-m-d')] = "Thi $subject";
                    $totalHours += 2;
                }
                return $subject;
            }
        }
        return '';
    }

    protected function addDaysSkippingWeekends($date, $days)
    {
        while ($days > 0) {
            $date->addDay();
            if ($date->dayOfWeek !== Carbon::SATURDAY && $date->dayOfWeek !== Carbon::SUNDAY) {
                $days--;
            }
        }
        return $date;
    }

    public function headings(): array
    {
        return [
            'NGÀY', '-', '', 'TUẦN', 'GIỜ HỌC', 'THỨ HAI', 'THỨ BA', 'THỨ TƯ', 'THỨ NĂM', 'THỨ SÁU'
        ];
    }

    public function title(): string
    {
        return 'tkb';
    }

    public function startCell(): string
    {
        return 'A9';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Times New Roman');

        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('A2:J2');
        $sheet->mergeCells('A3:J3');
        $sheet->mergeCells('A5:J5');
        $sheet->mergeCells('A9:C9');

        $sheet->getColumnDimension('A')->setWidth(12.66);
        $sheet->getColumnDimension('B')->setWidth(2.55);
        $sheet->getColumnDimension('C')->setWidth(11.55);
        $sheet->getColumnDimension('D')->setWidth(6.10);
        $sheet->getColumnDimension('E')->setWidth(12.33);
        $sheet->getColumnDimension('F')->setWidth(24.66);
        $sheet->getColumnDimension('G')->setWidth(24.66);
        $sheet->getColumnDimension('H')->setWidth(24.66);
        $sheet->getColumnDimension('I')->setWidth(25.55);
        $sheet->getColumnDimension('J')->setWidth(25.33);

        $sheet->getRowDimension(5)->setRowHeight(41.3);
        $sheet->getRowDimension(9)->setRowHeight(33.8);
        $sheet->getRowDimension(10)->setRowHeight(7.5);

        $sheet->setCellValue('A1', 'TRUNG TÂM CÔNG NGHỆ PHẦN MỀM ĐẠI HỌC CẦN THƠ');
        $sheet->setCellValue('A2', 'CANTHO UNIVERSITY SOFTWARE CENTER');
        $sheet->setCellValue('A3', 'Khu III, Đại học Cần Thơ – 01 Lý Tự Trọng, Tp. Cần Thơ – Tel: 0292.3731072 & Fax: 0292.3731071 – Email: cusc@ctu.edu.vn');
        $sheet->setCellValue('A5', $this->tkb->TenTKB);
        $sheet->setCellValue('C7', 'Mã Lớp:');
        $sheet->setCellValue('D7', $this->tkb->MaLop);
        $sheet->setCellValue('I6', 'Bắt đầu học từ ngày: ');
        $sheet->setCellValue('J6', Carbon::parse($this->tkb->NgayHoc)->format('d/m/Y'));
        $sheet->setCellValue('I7', 'Học Lý thuyết tại phòng: ');
        $sheet->setCellValue('J7', $this->phonglt->TenPhong);
        $sheet->setCellValue('I8', 'Học Thực hành tại phòng: ');
        $sheet->setCellValue('J8', $this->phongth->TenPhong);
        $sheet->setCellValue('D8', 'Ver ' . $this->chuongtrinh->PhienBan);
        $sheet->setCellValue('E8', Carbon::parse($this->chuongtrinh->NgayTrienKhaiPB)->format('d/m/Y'));

        $sheet->getStyle('A1')->getFont()->setSize(11)->setBold(true);
        $sheet->getStyle('A2')->getFont()->setSize(17)->setBold(true);
        $sheet->getStyle('A3')->getFont()->setSize(10)->setItalic(true);
        $sheet->getStyle('A5')->getFont()->setSize(18)->setBold(true);
        $sheet->getStyle('A5')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:J5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C7')->getFont()->setSize(12)->setBold(true);
        $sheet->getStyle('D7')->getFont()->setSize(12)->setBold(true)->getColor()->setRGB('FF0000');
        $sheet->getStyle('D8')->getFont()->setSize(10);
        $sheet->getStyle('E8')->getFont()->setSize(9)->setItalic(true);
        $sheet->getStyle('E8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I6:I8')->getFont()->setSize(12)->setBold(true);
        $sheet->getStyle('J6:J8')->getFont()->setSize(12)->setBold(true)->getColor()->setRGB('FF0000');

        $sheet->getStyle('A9:J9')->getFont()->setSize(10)->setBold(true);
        $sheet->getStyle('A9:J9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A9:J9')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A9:J9')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('BFBFBF');
        
        
        $collection = $this->collection();
        $rowCount = $collection->count();
    
        for ($row = 11; $row < 13 + $rowCount; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(53.3);
            $sheet->getStyle("A$row:J$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("A$row:J$row")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $sheet->getStyle("A$row:J$row")->getAlignment()->setWrapText(true);
    
            $sheet->getStyle("D$row")->getFont()->setBold(true);
            $sheet->getStyle("E$row")->getFont()->setBold(true);
        }
    
        return [
            'A1:J1' => ['font' => ['bold' => true, 'size' => 11]],
            'A2:J2' => ['font' => ['bold' => true, 'size' => 17]],
            'A3:J3' => ['font' => ['italic' => true, 'size' => 10]],
            'A5' => ['font' => ['bold' => true, 'size' => 18]],
            'A9:J9' => [
                'font' => ['bold' => true, 'size' => 10],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
            'A10:J' . (12 + $rowCount) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }
}
