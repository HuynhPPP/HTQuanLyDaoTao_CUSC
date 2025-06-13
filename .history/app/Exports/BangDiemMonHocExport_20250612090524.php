<?php

namespace App\Exports;

use App\Models\LichThi;
use App\Models\DiemThi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;

class BangDiemMonHocExport implements FromCollection, WithHeadings, WithTitle, WithEvents
{
    protected $maLichThi;

    public function __construct($maLichThi)
    {
        $this->maLichThi = $maLichThi;
    }

    public function collection()
    {
        $diemThi = DiemThi::with(['sinhVien', 'lichThi.monHoc'])
            ->where('MaLichThi', $this->maLichThi)
            ->get();

        return $diemThi->map(function ($item, $index) {
            return [
                'STT' => $index + 1,
                'MaSV' => $item->MaSV,
                'HoTen' => $item->sinhVien->HoTen,
                'DiemCC' => $item->DiemCC ?? 0,
                'DiemGK' => $item->DiemGK ?? 0,
                'DiemCK' => $item->DiemCK ?? 0,
                'DiemTong' => $this->tinhDiemTong($item),
                'XepLoai' => $this->xepLoaiHocLuc($this->tinhDiemTong($item))
            ];
        });
    }

    public function headings(): array
    {
        return [
            'STT',
            'Mã SV', 
            'Họ Tên',
            'Điểm Chuyên Cần',
            'Điểm Giữa Kỳ', 
            'Điểm Cuối Kỳ',
            'Điểm Tổng',
            'Xếp Loại'
        ];
    }

    public function title(): string
    {
        return 'Bảng Điểm Môn Học';
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lichThi = LichThi::with(['lopHoc', 'monHoc'])->find($this->maLichThi);

                // Thêm tiêu đề và thông tin
                $sheet->insertNewRowBefore(1, 10);
                
                $sheet->mergeCells('A1:H1')->setCellValue('A1', 'TRUNG TÂM CÔNG NGHỆ PHẦN MỀM ĐẠI HỌC CẦN THƠ');
                $sheet->mergeCells('A2:H2')->setCellValue('A2', 'BẢNG ĐIỂM MÔN HỌC');
                $sheet->mergeCells('A3:H3')->setCellValue('A3', "Môn: {$lichThi->monHoc->TenMH} - Lớp: {$lichThi->MaLop}");

                // Style tiêu đề
                foreach (range(1, 3) as $row) {
                    $sheet->getStyle("A$row")->getFont()->setBold(true);
                    $sheet->getStyle("A$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
            },
        ];
    }

    private function tinhDiemTong($diemThi)
    {
        // Tính điểm tổng (ví dụ: 20% chuyên cần, 30% giữa kỳ, 50% cuối kỳ)
        return round(
            ($diemThi->DiemCC ?? 0) * 0.2 + 
            ($diemThi->DiemGK ?? 0) * 0.3 + 
            ($diemThi->DiemCK ?? 0) * 0.5, 
            2
        );
    }

    private function xepLoaiHocLuc($diemTong)
    {
        if ($diemTong >= 8.5) return 'Giỏi';
        if ($diemTong >= 7.0) return 'Khá';
        if ($diemTong >= 5.5) return 'Trung Bình';
        if ($diemTong >= 4.0) return 'Yếu';
        return 'Kém';
    }
} 