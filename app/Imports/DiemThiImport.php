<?php
namespace App\Imports;

use App\Models\DiemThi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DiemThiImport implements ToModel, WithHeadingRow
{
    protected $maLop;
    protected $tenMH;

    public function __construct($maLop, $tenMH)
    {
        $this->maLop = $maLop;
        $this->tenMH = $tenMH;
    }

    public function model(array $row)
    {
        return new DiemThi([
            'MaSV' => $row['ma_sv'],
            'TenMH' => $this->tenMH,
            'MaLop' => $this->maLop,
            'LanThi' => $row['lan_thi'] ?? 1,
            'Diem' => $row['diem'],
            'GhiChu' => $row['ghi_chu'] ?? null,
        ]);
    }
}