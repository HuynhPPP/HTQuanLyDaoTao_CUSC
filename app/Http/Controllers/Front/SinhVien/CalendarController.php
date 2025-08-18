<?php

namespace App\Http\Controllers\Front\SinhVien;

use App\Http\Controllers\Controller;
use App\Models\sinhvien;
use App\Models\LdapAccount;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\lophoc;
use App\Models\danhsachphong;
use App\Models\chuongtrinh;
use App\Models\tkb;
use App\Models\danhsachmonhoc;
use App\Models\danhsachngaynghi;
use App\Models\ngaytuhoc;
use App\Models\hocki;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ScheduleExport;
class CalendarController extends Controller
{
    public function StudentCalendar(Request $request)
    {
        $username = session('user');
        if (!$username) {
            return redirect()->route('login')->with('error', 'Bạn chưa đăng nhập!');
        }

        // 1. Lấy MaSV từ bảng ldap_accounts
        $ldapAccount = LdapAccount::where('username', $username)->first();
        if (!$ldapAccount) {
            return view('frontend.sinhvien.lich_hoc.calendar_index')->with('error', 'Không tìm thấy tài khoản LDAP.');
        }

        // 2. Lấy sinh viên từ bảng sinhvien
        $sinhVien = sinhvien::where('MaSV', $ldapAccount->MaTaiKhoan)->first();
        if (!$sinhVien) {
            return view('frontend.sinhvien.lich_hoc.calendar_index')->with('error', 'Không tìm thấy thông tin sinh viên.');
        }

        // 3. Lấy mã lớp từ bảng danhsachsv (giả sử 1 sinh viên chỉ thuộc 1 lớp)
        $danhSachLop = $sinhVien->danhSachLop()->first();
        if (!$danhSachLop) {
            return view('frontend.sinhvien.lich_hoc.calendar_index')->with('error', 'Không tìm thấy lớp của sinh viên.');
        }
        $maLop = $danhSachLop->MaLop;

        // 4. Lấy thời khoá biểu và các bước tiếp theo như cũ
        $schedule = tkb::where('MaLop', $maLop)->latest('NgayHoc')->first();
        if (!$schedule) {
            return view('frontend.sinhvien.lich_hoc.calendar_index')->with('error', 'Không tìm thấy thời khoá biểu.');
        }

        $chuongtrinh = chuongtrinh::find(optional(lophoc::find($schedule->MaLop))->MaChuongTrinh);
        $hocki = hocki::find($schedule->MaHK);
        $dsmh = danhsachmonhoc::where('MaHK', $hocki->MaHK)->first();
        $ngaynghis = danhsachngaynghi::with('ngayNghi')->where('TenTKB', $schedule->TenTKB)->get()->pluck('ngayNghi');
        $monhocs = danhsachmonhoc::where('MaHK', $hocki->MaHK)->with('khungGio')->get();
        $ngaytuhocs = ngaytuhoc::where('TenTKB', $schedule->TenTKB)->get();

        // Lấy toàn bộ phòng học của lớp, keyBy ngày sử dụng
        $phongHocTheoNgay = danhsachphong::where('MaLop', $maLop)->get()->keyBy('NgaySuDung');

        // Build scheduleMatrix giống SchedulesController
        $startDate = Carbon::parse($schedule->NgayHoc);
        $totalHours = $hocki->TongGioTrienKhai;
        $emptyDays = 0;
        $weekStartDate = $startDate->copy()->startOfWeek();
        for ($date = $weekStartDate->copy(); $date->lt($startDate); $date->addDay()) {
            if ($date->dayOfWeek !== Carbon::SATURDAY && $date->dayOfWeek !== Carbon::SUNDAY) {
                $emptyDays++;
            }
        }
        $totalHours += $emptyDays * 2;
        $totalWeeks = ceil($totalHours / 20);

        // Các ngày trong tuần
        switch ($schedule->ngayHocType ?? 'all') {
            case 'chan':
                $weekDays = ['THỨ HAI', 'THỨ TƯ', 'THỨ SÁU'];
                break;
            case 'le':
                $weekDays = ['THỨ BA', 'THỨ NĂM', 'THỨ BẢY'];
                break;
            default:
                $weekDays = ['THỨ HAI', 'THỨ BA', 'THỨ TƯ', 'THỨ NĂM', 'THỨ SÁU', 'THỨ BẢY'];
        }

        // Lọc môn học có giờ triển khai > 0 và xây dựng mapping thời gian theo môn
        $filteredMonHocs = [];
        $thoiGianTheoMon = [];
        foreach ($monhocs as $monhoc) {
            if ($monhoc && $monhoc->GioTrienKhai > 0) {
                $filteredMonHocs[] = $monhoc;
            }
            if ($monhoc) {
                $thoiGianTheoMon[$monhoc->MaMH] = [
                    'tenKhungGio' => $monhoc->TenKhungGio,
                    'thoiGian' => optional($monhoc->khungGio)->ThoiGian,
                ];
            }
        }
        $subjectOccurrences = [];
        $subjectCount = count($filteredMonHocs);
        foreach ($filteredMonHocs as $index => $monhoc) {
            $subjectOccurrences[$monhoc->MaMH] = [
                'TenMH' => $monhoc->TenMH,
                'first' => null,
                'last' => null,
                'remaining' => $monhoc->GioTrienKhai,
            ];
            if ($index === $subjectCount - 1) {
                $subjectOccurrences[$monhoc->MaMH]['lastSubject'] = true;
            }
        }

        // Xử lý ngày tự học
        $selfStudyDays = [];
        foreach ($ngaytuhocs as $ngaytuhoc) {
            $selfStudyStart = Carbon::parse($ngaytuhoc->NgayBDTuHoc);
            $selfStudyEnd = Carbon::parse($ngaytuhoc->NgayKTTuHoc);
            while ($selfStudyStart->lte($selfStudyEnd)) {
                if ($selfStudyStart->dayOfWeek !== Carbon::SATURDAY && $selfStudyStart->dayOfWeek !== Carbon::SUNDAY) {
                    $selfStudyDays[$selfStudyStart->format('Y-m-d')] = $ngaytuhoc->TenNgayTuHoc;
                    $totalHours += 2;
                }
                $selfStudyStart->addDay();
            }
        }
        // Xử lý ngày nghỉ
        $holidayDates = [];
        foreach ($ngaynghis as $ngaynghi) {
            $holidayStart = Carbon::parse($ngaynghi->NgayBDNghi);
            $holidayEnd = Carbon::parse($ngaynghi->NgayKT);
            while ($holidayStart->lte($holidayEnd)) {
                if ($holidayStart->dayOfWeek !== Carbon::SATURDAY && $holidayStart->dayOfWeek !== Carbon::SUNDAY) {
                    $holidayDates[$holidayStart->format('Y-m-d')] = $ngaynghi->TenNgayNghi;
                    $totalHours += 2;
                }
                $holidayStart->addDay();
            }
        }
        $examCounter = 0;
        $examDays = [];
        $getSubjectForDay = function (&$subjectOccurrences, $currentDate, &$totalHours, &$examDays, $holidayDates, &$examCounter) {
            foreach ($subjectOccurrences as $subject => &$details) {
                if ($details['remaining'] > 0) {
                    if (is_null($details['first'])) {
                        $details['first'] = $currentDate;
                    }
                    $details['remaining'] -= 4;
                    if ($details['remaining'] <= 0) {
                        $details['last'] = $currentDate;
                        $examDate = Carbon::parse($currentDate->format('Y-m-d'))->addWeek();
                        while (isset($holidayDates[$examDate->format('Y-m-d')])) {
                            $examDate->addDay();
                        }
                        $examCounter++;
                        $examDays[$examDate->format('Y-m-d')] = [
                            'subject_string' => 'Thi ' . $subjectOccurrences[$subject]['TenMH'] . " ($subject, E$examCounter) - L",
                            'MaMH' => $subject,
                        ];
                        $totalHours += 2;
                    }
                    return $subject;
                }
            }
            return '';
        };

        // Tạo scheduleMatrix
        $scheduleMatrix = [];
        for ($week = 1; $week <= $totalWeeks; $week++) {
            $weekStart = $startDate->copy()->addWeeks($week - 1)->startOfWeek();
            $scheduleMatrix[$week] = [];
            foreach ($weekDays as $dayIndex => $day) {
                $currentDate = $weekStart->copy()->addDays($dayIndex);
                $subject = '';
                $inlineStyle = '';
                if ($currentDate->gte($startDate)) {
                    if (isset($examDays[$currentDate->format('Y-m-d')])) {
                        $subject = $examDays[$currentDate->format('Y-m-d')]['subject_string'];
                        $inlineStyle = 'color: blue; font-weight: bold;';
                    } elseif (isset($selfStudyDays[$currentDate->format('Y-m-d')])) {
                        $subject = $selfStudyDays[$currentDate->format('Y-m-d')];
                        $inlineStyle = 'color: green; font-weight: bold;';
                    } elseif (isset($holidayDates[$currentDate->format('Y-m-d')])) {
                        $subject = $holidayDates[$currentDate->format('Y-m-d')];
                        $inlineStyle = 'background-color: yellow;';
                    } else {
                        $subject = $getSubjectForDay(
                            $subjectOccurrences,
                            $currentDate,
                            $totalHours,
                            $examDays,
                            $holidayDates,
                            $examCounter
                        );
                        if ($subject) {
                            if (
                                isset($subjectOccurrences[$subject]['first']) &&
                                $subjectOccurrences[$subject]['first'] instanceof Carbon &&
                                $subjectOccurrences[$subject]['first']->eq($currentDate)
                            ) {
                                $inlineStyle = 'color: red; font-weight: bold;';
                            } elseif (
                                isset($subjectOccurrences[$subject]['last']) &&
                                $subjectOccurrences[$subject]['last'] instanceof Carbon &&
                                $subjectOccurrences[$subject]['last']->eq($currentDate)
                            ) {
                                $inlineStyle = 'color: purple; font-weight: bold;';
                            }
                        }
                    }
                }
                $scheduleMatrix[$week][$day] = [
                    'date' => $currentDate->format('d/m/Y'),
                    'subject' => $subject,
                    'style' => $inlineStyle,
                ];
            }
        }

        // Xác định tuần hiện tại theo ngày thực tế để làm mặc định
        $today = Carbon::today();
        $endDate = $startDate->copy()->addWeeks($totalWeeks - 1)->endOfWeek();
        if ($today->lt($startDate)) {
            $selectedWeekDefault = 1;
        } elseif ($today->gt($endDate)) {
            $selectedWeekDefault = $totalWeeks;
        } else {
            $selectedWeekDefault = $startDate->copy()->startOfWeek()->diffInWeeks($today->copy()->startOfWeek()) + 1;
        }

        // Lấy tuần từ request (nếu có), nếu không dùng mặc định vừa tính
        $selectedWeek = (int) ($request->input('week', $selectedWeekDefault));
        if ($selectedWeek < 1) $selectedWeek = 1;
        if ($selectedWeek > $totalWeeks) $selectedWeek = $totalWeeks;
        $viewMode = $request->input('viewMode', 'week'); // 'week' hoặc 'all'

        $exportUrl = route('student.calendar.export', [], false);

        return view('frontend.sinhvien.lich_hoc.calendar_index', compact(
            'schedule',
            'chuongtrinh',
            'hocki',
            'dsmh',
            'ngaynghis',
            'monhocs',
            'ngaytuhocs',
            'scheduleMatrix',
            'selectedWeek',
            'totalWeeks',
            'weekDays',
            'subjectOccurrences',
            'viewMode',
            'phongHocTheoNgay',
            'thoiGianTheoMon',
            'exportUrl'
        ));
    }

    public function exportSchedule(Request $request)
    {
        $username = session('user');
        if (!$username) {
            return redirect()->route('login')->with('error', 'Bạn chưa đăng nhập!');
        }

        $ldapAccount = LdapAccount::where('username', $username)->first();
        if (!$ldapAccount) {
            return back()->with('error', 'Không tìm thấy tài khoản LDAP.');
        }

        $sinhVien = sinhvien::where('MaSV', $ldapAccount->MaTaiKhoan)->first();
        if (!$sinhVien) {
            return back()->with('error', 'Không tìm thấy thông tin sinh viên.');
        }

        $danhSachLop = $sinhVien->danhSachLop()->first();
        if (!$danhSachLop) {
            return back()->with('error', 'Không tìm thấy lớp của sinh viên.');
        }

        $schedule = tkb::where('MaLop', $danhSachLop->MaLop)->latest('NgayHoc')->first();
        if (!$schedule) {
            return back()->with('error', 'Không tìm thấy thời khoá biểu.');
        }

        $lophoc = lophoc::where('MaLop', $schedule->MaLop)->first();
        $chuongtrinh = chuongtrinh::where('MaChuongTrinh', optional($lophoc)->MaChuongTrinh)->first();
        $phonglt = danhsachphong::where('MaLop', $lophoc->MaLop)->where('TenPhong', 'LIKE', '%Class%')->first();
        $phongth = danhsachphong::where('MaLop', $lophoc->MaLop)->where('TenPhong', 'LIKE', '%Lab%')->first();
        $hocki = hocki::where('MaHK', $schedule->MaHK)->first();
        $dsmh = danhsachmonhoc::where('MaHK', $hocki->MaHK)->first();
        $ngaynghis = danhsachngaynghi::where('TenTKB', $schedule->TenTKB)->get()->pluck('ngayNghi');
        $monhocs = danhsachmonhoc::where('MaHK', $hocki->MaHK)->get();
        $ngaytuhocs = ngaytuhoc::where('TenTKB', $schedule->TenTKB)->get();

        $fileName = 'lich_hoc_thi_' . $schedule->MaLop . '.xlsx';
        return Excel::download(new ScheduleExport($schedule, $chuongtrinh, $phonglt, $phongth, $dsmh, $hocki, $ngaynghis, $monhocs, $ngaytuhocs), $fileName);
    }
}
