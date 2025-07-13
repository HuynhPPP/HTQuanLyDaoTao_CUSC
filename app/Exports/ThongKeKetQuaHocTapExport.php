<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\DiemMonHocSheet;
use App\Exports\Sheets\DatChuaDatSheet;
use App\Exports\Sheets\TongKetHocLucSheet;

class ThongKeKetQuaHocTapExport implements WithMultipleSheets
{
    protected $lop, $theoMon, $thongKeDat, $tongKet;

    public function __construct($lop, $theoMon, $thongKeDat, $tongKet)
    {
        $this->lop = $lop;
        $this->theoMon = $theoMon;
        $this->thongKeDat = $thongKeDat;
        $this->tongKet = $tongKet;
    }

    public function sheets(): array
    {
        return [
            new DiemMonHocSheet($this->lop, $this->theoMon),
            new DatChuaDatSheet($this->lop, $this->thongKeDat),
            new TongKetHocLucSheet($this->lop, $this->tongKet),
        ];
    }
}

