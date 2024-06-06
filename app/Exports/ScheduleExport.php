<?php

namespace App\Exports;

use App\Models\tkb;
use App\Models\chuongtrinh;
use App\Models\theodoimhsapbatdau;
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
    protected $theodoimh;

    public function __construct($tkb, $chuongtrinh, $theodoimh, )
    {
        $this->tkb = $tkb;
        $this->chuongtrinh = $chuongtrinh;
        $this->theodoimh = $theodoimh;

        \Log::info('ScheduleExport Construct:', [
            'tkb' => $tkb,
            'chuongtrinh' => $chuongtrinh,
            'theodoimh' => $theodoimh
        ]);
    }

    public function collection()
    {
        $data = [];
        $startDate = $this->theodoimh ? Carbon::parse($this->theodoimh->NgayBatDau) : null;

        for ($i = 1; $i <= $this->tkb->TuanHoc; $i++) {
            $weekStart = $startDate ? $startDate->copy()->addWeeks($i - 1)->startOfWeek() : null;
            $weekEnd = $startDate ? $weekStart->copy()->endOfWeek()->subDays(2) : null;

            $data[] = [
                'Ngày' => $weekStart ? $weekStart->format('d/m/Y') . ' - ' . $weekEnd->format('d/m/Y') : '',
                'Tuần' => $i,
                'Giờ học' => '7:00-9:00',
                'THỨ HAI' => "Hàng $i",
                'THỨ BA' => "Hàng $i",
                'THỨ TƯ' => "Hàng $i",
                'THỨ NĂM' => "Hàng $i",
                'THỨ SÁU' => "Hàng $i",
            ];

            $data[] = [
                'Ngày' => '',
                'Tuần' => '',
                'Giờ học' => '13:00-15:00',
                'THỨ HAI' => "Hàng $i",
                'THỨ BA' => "Hàng $i",
                'THỨ TƯ' => "Hàng $i",
                'THỨ NĂM' => "Hàng $i",
                'THỨ SÁU' => "Hàng $i",
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Ngày',
            'Tuần',
            'Giờ học',
            'THỨ HAI',
            'THỨ BA',
            'THỨ TƯ',
            'THỨ NĂM',
            'THỨ SÁU',
        ];
    }

    public function title(): string
    {
        return 'tkb';
    }

    public function startCell(): string
    {
        return 'A8';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');
        $sheet->mergeCells('A3:H3');
        $sheet->mergeCells('A4:H4');
        $sheet->mergeCells('A5:H5');

        $sheet->setCellValue('A1', 'TRUNG TÂM CÔNG NGHỆ PHẦN MỀM ĐẠI HỌC CẦN THƠ');
        $sheet->setCellValue('A2', 'CANTHO UNIVERSITY SOFTWARE CENTER');
        $sheet->setCellValue('A3', 'Khu III, Đại học Cần Thơ – 01 Lý Tự Trọng, Tp. Cần Thơ – Tel: 0292.3731072 & Fax: 0292.3731071 – Email: cusc@ctu.edu.vn');
        $sheet->setCellValue('A4', $this->tkb->TenTKB);
        $sheet->setCellValue('A5', 'Mã lớp: ' . $this->tkb->MaLop . ' - Ver: ' . $this->chuongtrinh->PhienBan . ' - Ngày triển khai: ' . Carbon::parse($this->chuongtrinh->NgayTrienKhaiPB)->format('d/m/Y'));
        $sheet->setCellValue('A6', 'Bắt đầu học từ ngày: ' .  Carbon::parse($this->theodoimh->NgayBatDau)->format('d/m/Y'));
        $sheet->setCellValue('A7', 'Học Lý thuyết tại phòng: ' . $this->theodoimh->TenPhong );

        $sheet->getStyle('A1:H7')->getFont()->setBold(true);
        $sheet->getStyle('A1:H5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        return [
            // Styling for the headings
            8 => ['font' => ['bold' => true]],
        ];
    }
}


