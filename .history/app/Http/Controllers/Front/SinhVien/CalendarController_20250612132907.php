<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\sinhvien;
use App\Models\LdapAccount;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
class CalendarController extends Controller
{
    public function StudentCalendar()
    {
        $sinhViens = sinhvien::with(['hosotuyensinh', 'danhSachLop'])->get();
        return view('schedules.sinhvien.calendar_index', compact('sinhViens'));
    }
}
