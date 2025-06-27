<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TeacherScheduleExport implements FromArray, WithStyles, WithTitle, WithCustomStartCell
{
    protected $schedule;
    protected $giangDays;
    protected $ngaynghis;
    protected $ngaytuhocs;
    protected $hocki;
    protected $chuongTrinhName;
    protected $phonglt;
    protected $phongth;
    protected $monhocs;

    public function __construct($schedule, $giangDays, $ngaynghis, $ngaytuhocs, $hocki, $chuongTrinhName, $phonglt, $phongth, $monhocs)
    {
        $this->schedule = $schedule;
        $this->giangDays = $giangDays;
        $this->ngaynghis = $ngaynghis;
        $this->ngaytuhocs = $ngaytuhocs;
        $this->hocki = $hocki;
        $this->chuongTrinhName = $chuongTrinhName;
        $this->phonglt = $phonglt;
        $this->phongth = $phongth;
        $this->monhocs = $monhocs;
    }

    public function array(): array
    {
        $rows = [];
        $rows[] = ['TRUNG TÂM CÔNG NGHỆ PHẦN MỀM ĐẠI HỌC CẦN THƠ', '', '', '', '', '', '', '', ''];
        $rows[] = ['CANTHO UNIVERSITY SOFTWARE CENTER', '', '', '', '', '', '', '', ''];
        $rows[] = ['Khu III, Đại học Cần Thơ – 01 Lý Tự Trọng, Tp. Cần Thơ – Tel: 0292.3731072 & Fax: 0292.3731071 – Email: cusc@ctu.edu.vn', '', '', '', '', '', '', '', ''];
        $rows[] = [''];
        $rows[] = ['LỊCH GIẢNG DẠY LỚP ' . $this->schedule->MaLop . ' - ' . $this->chuongTrinhName, '', '', '', '', '', '', '', ''];
        $rows[] = ['HỌC KỲ ' . $this->hocki->TenHK, '', '', '', '', '', '', '', ''];
        $rows[] = [''];
        $rows[] = ['Bắt đầu học từ ngày: ' . $this->schedule->NgayHoc, '', '', '', '', '', '', '', ''];
        $rows[] = ['Phòng lý thuyết: ' . ($this->phonglt->TenPhong ?? ''), '', '', '', '', '', '', '', ''];
        $rows[] = ['Phòng thực hành: ' . ($this->phongth->TenPhong ?? ''), '', '', '', '', '', '', '', ''];
        $rows[] = [''];

        $weekDays = ['THỨ HAI', 'THỨ BA', 'THỨ TƯ', 'THỨ NĂM', 'THỨ SÁU', 'THỨ BẢY'];

        foreach ($this->giangDays as $maGV => $teacherData) {
            $gv = $teacherData['info'];
            $rows[] = [''];
            $rows[] = ['Giảng viên: ' . $gv->HoTenGV, '', '', '', '', '', '', '', ''];
            $rows[] = ['NGÀY', "TUẦN", 'GIỜ HỌC', ...$weekDays];
            foreach ($teacherData['schedule'] as $week => $days) {
                $weekDates = array_column($days, 'date');
                $ngayCell = reset($weekDates) . "\n-\n" . end($weekDates);
                $row = [
                    $ngayCell,
                    $week,
                    'Sáng', // hoặc lấy từ dữ liệu nếu có
                ];
                foreach ($weekDays as $dayName) {
                    $dayData = $days[$dayName] ?? null;
                    if (!$dayData) {
                        $row[] = '-';
                        continue;
                    }
                    if ($dayData['is_exam'] ?? false) {
                        $maMH = $dayData['MaMH'] ?? null;
                        $tenMH = '';
                        if ($maMH) {
                            $tenMH = $this->monhocs->first(function($mh) use ($maMH) { return $mh->MaMH == $maMH; });
                            $tenMH = $tenMH ? $tenMH->TenMH : '';
                        }
                        $row[] = $tenMH ? ('Thi ' . $tenMH) : '-';
                    } elseif ($dayData['is_holiday'] ?? false) {
                        $row[] = $dayData['subject'] ?: 'nghỉ lễ';
                    } elseif ($dayData['is_self_study_day'] ?? false) {
                        $row[] = $dayData['subject'] ?: 'self-study';
                    } elseif (!empty($dayData['subject'])) {
                        $tenMH = $this->monhocs->first(function($mh) use ($dayData) { return $mh->MaMH == $dayData['subject']; });
                        $row[] = $tenMH ? $tenMH->TenMH : $dayData['subject'];
                    } else {
                        $row[] = '-';
                    }
                }
                $rows[] = $row;
            }
            $rows[] = [''];
        }
        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Times New Roman');
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');
        $sheet->mergeCells('A5:I5');
        $sheet->mergeCells('A6:I6');
        $sheet->mergeCells('A8:I8');
        $sheet->mergeCells('A9:I9');
        $sheet->mergeCells('A10:I10');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:A3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A6')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A5:A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A5:A6')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A8:A10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A8:A10')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C:I')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:I' . $sheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A1:I' . $sheet->getHighestRow())->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:I' . $sheet->getHighestRow())->getAlignment()->setWrapText(true);
        foreach (range(1, $sheet->getHighestRow()) as $row) {
            $cell = 'A' . $row;
            if ($sheet->getCell($cell)->getValue() === 'NGÀY') {
                $sheet->getStyle('A' . $row . ':I' . $row)->getFont()->setBold(true);
                $sheet->getStyle('A' . $row . ':I' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('B7B7B7');
            }
            if (strpos((string)$sheet->getCell($cell)->getValue(), 'Giảng viên:') === 0) {
                $sheet->getStyle('A' . $row . ':I' . $row)->getFont()->setBold(true)->setSize(13);
                $sheet->mergeCells('A' . $row . ':I' . $row);
            }
            if ($sheet->getCell($cell)->getValue() === null || $sheet->getCell($cell)->getValue() === '') {
                $sheet->getRowDimension($row)->setRowHeight(8);
            } else {
                $isWeekRow = is_numeric($sheet->getCell('B' . $row)->getValue());
                if ($isWeekRow) {
                    $sheet->getRowDimension($row)->setRowHeight(50);
                } else {
                    $sheet->getRowDimension($row)->setRowHeight(32);
                }
            }
        }
        $sheet->getColumnDimension('A')->setWidth(18);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(18);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(18);
        $sheet->getColumnDimension('H')->setWidth(18);
        $sheet->getColumnDimension('I')->setWidth(18);
        $sheet->getStyle('B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('B')->getAlignment()->setWrapText(true);
        return [];
    }

    public function title(): string
    {
        return 'LichGiangVien';
    }

    public function startCell(): string
    {
        return 'A1';
    }
} 