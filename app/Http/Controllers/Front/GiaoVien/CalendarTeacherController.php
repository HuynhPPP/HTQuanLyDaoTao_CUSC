<?php

namespace App\Http\Controllers\Front\GiaoVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GiangDay;
use App\Models\LdapAccount;
use App\Models\giaovien;
use App\Models\tkb;
use App\Models\lophoc;
use App\Models\chuongtrinh;
use App\Models\danhsachphong;
use App\Models\danhsachmonhoc;
use App\Models\hocki;
use App\Models\ngaytuhoc;
use App\Models\danhsachngaynghi;
use Carbon\Carbon;

class CalendarTeacherController extends Controller
{
    public function teacherSchedule(Request $request)
    {
        $username = session('user');
        if (!$username) {
            return redirect()->route('login')->with('error', 'Bạn chưa đăng nhập!');
        }

        // 1. Lấy MaSV từ bảng ldap_accounts
        $ldapAccount = LdapAccount::where('username', $username)->first();
        // Lấy mã giảng viên từ bảng giangvien (giả sử username là mã GV hoặc có thể join từ bảng tài khoản)
        $giangVien = giaovien::where('MaGV', $ldapAccount->MaTaiKhoan)->first();
        if (!$giangVien) {
            return view('frontend.giangvien.lich_giang_day.teacher_schedule')->with('error', 'Không tìm thấy thông tin giảng viên.');
        }
        $maGV = $giangVien->MaGV;

        // Lấy các lớp/môn mà giảng viên này dạy
        $giangDays = GiangDay::where('MaGV', $maGV)->get();
        if ($giangDays->isEmpty()) {
            return view('frontend.giangvien.lich_giang_day.teacher_schedule')->with('error', 'Bạn chưa được phân công giảng dạy lớp nào.');
        }

        // Lấy tất cả các MaLop mà GV dạy
        $maLops = $giangDays->pluck('MaLop')->unique()->values();
        $tkbs = tkb::whereIn('MaLop', $maLops)->get();
        if ($tkbs->isEmpty()) {
            return view('frontend.giangvien.lich_giang_day.teacher_schedule')->with('error', 'Không tìm thấy thời khoá biểu cho các lớp bạn dạy.');
        }

        // Chọn 1 lớp để xem lịch (hoặc cho phép chọn lớp)
        $selectedMaLop = $request->input('lop', $maLops->first());
        $schedule = $tkbs->where('MaLop', $selectedMaLop)->first();
        if (!$schedule) {
            return view('frontend.giangvien.lich_giang_day.teacher_schedule')->with('error', 'Không tìm thấy thời khoá biểu cho lớp đã chọn.');
        }

        $lophoc = lophoc::find($schedule->MaLop);
        $chuongtrinh = chuongtrinh::find($lophoc->MaChuongTrinh ?? null);
        $hocki = hocki::find($schedule->MaHK);
        $dsmh = danhsachmonhoc::where('MaHK', $hocki->MaHK)->first();
        $monhocs = danhsachmonhoc::where('MaHK', $hocki->MaHK)->get();
        $ngaytuhocs = ngaytuhoc::where('TenTKB', $schedule->TenTKB)->get();
        $ngaynghis = danhsachngaynghi::with('ngayNghi')->where('TenTKB', $schedule->TenTKB)->get()->pluck('ngayNghi');
        $phongHocTheoNgay = danhsachphong::where('MaLop', $schedule->MaLop)->get()->keyBy('NgaySuDung');

        // Build scheduleMatrix: chỉ hiển thị các tiết mà giảng viên này phụ trách
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

        // Lọc các môn mà GV này dạy
        $maMHsGVDay = $giangDays->pluck('MaMH')->unique()->values();
        $filteredMonHocs = $monhocs->whereIn('MaMH', $maMHsGVDay);
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

        // Tạo scheduleMatrix: chỉ hiển thị tiết mà GV này dạy hoặc các ngày nghỉ/tự học/thi
        $scheduleMatrix = [];
        for ($week = 1; $week <= $totalWeeks; $week++) {
            $weekStart = $startDate->copy()->addWeeks($week - 1)->startOfWeek();
            $scheduleMatrix[$week] = [];
            foreach ($weekDays as $dayIndex => $day) {
                $currentDate = $weekStart->copy()->addDays($dayIndex);
                $subject = '';
                $inlineStyle = '';
                if ($currentDate->gte($startDate)) {
                    if (isset($selfStudyDays[$currentDate->format('Y-m-d')])) {
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
                        // Chỉ hiển thị nếu là môn mà GV này dạy
                        if ($subject && !$maMHsGVDay->contains($subject)) {
                            $subject = '';
                        }
                        if ($subject) {
                            if (isset($subjectOccurrences[$subject]['first']) && $subjectOccurrences[$subject]['first']->eq($currentDate)) {
                                $inlineStyle = 'color: red; font-weight: bold;';
                            } elseif (isset($subjectOccurrences[$subject]['last']) && $subjectOccurrences[$subject]['last']->eq($currentDate)) {
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

        // Lấy tuần hiện tại từ request, mặc định là 1
        $selectedWeek = $request->input('week', 1);
        $viewMode = $request->input('viewMode', 'week');

        return view('frontend.giangvien.lich_giang_day.teacher_schedule', compact(
            'schedule',
            'chuongtrinh',
            'hocki',
            'dsmh',
            'monhocs',
            'ngaytuhocs',
            'ngaynghis',
            'phongHocTheoNgay',
            'scheduleMatrix',
            'selectedWeek',
            'totalWeeks',
            'weekDays',
            'subjectOccurrences',
            'viewMode',
            'giangVien',
            'maLops',
            'selectedMaLop',
        ));
    }
}
