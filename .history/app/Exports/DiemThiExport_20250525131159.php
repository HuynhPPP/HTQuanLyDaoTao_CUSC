<?php
namespace App\Exports;

use App\Models\DiemThi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DiemThiExport implements FromCollection, WithHeadings
{
    protected $maLop;
    protected $tenMH;

    public function __construct($maLop, $tenMH)
    {
        $this->maLop = $maLop;
        $this->tenMH = $tenMH;
    }

    public function collection()
    {
        return DiemThi::where('MaLop', $this->maLop)
                     ->where('TenMH', $this->tenMH)
                     ->get();
    }

    public function headings(): array
    {
        return [
            'Mã SV',
            'Tên Môn Học',
            'Mã Lớp', 
            'Lần Thi',
            'Điểm',
            'Ghi Chú'
        ];
    }
}