<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ThongKeDoAnExport implements FromView
{
    protected $reports;

    public function __construct($reports)
    {
        $this->reports = $reports;
    }

    public function view(): View
    {
        return view('thong-ke.thongkebaocaodoan.excel', [
            'reports' => $this->reports
        ]);
    }
}
