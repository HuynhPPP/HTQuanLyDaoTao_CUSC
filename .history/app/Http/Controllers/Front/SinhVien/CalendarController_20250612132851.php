<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class CalendarController extends Controller
{
    public function StudentCalendar()
    {
        $sinhViens = sinhvien::with(['hosotuyensinh', 'danhSachLop'])->get();
        return view('schedules.sinhvien.calendar_index', compact('sinhViens'));
    }
}
