<?php

namespace App\Http\Controllers\Schedules;

use App\Http\Controllers\Controller;
use App\Models\khoadaotao;
use App\Models\GiangDay;
use App\Models\giaovien;
use App\Models\tkb;
use App\Models\phonghoc;
use App\Models\danhsachphong;
use App\Models\danhsachmonhoc;
use App\Models\danhsachngaynghi;
use App\Models\hocki;
use App\Models\lophoc;
use App\Models\khunggio;
use App\Models\ngaytuhoc;
use App\Models\ChuongTrinh;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ScheduleExport;
use Exception;
use Illuminate\Http\Request;

class SchedulesController extends Controller
{
    public function CreateSchedules()
    {
        if (session()->has('user')) {
            $data = [
                'khoadaotaos' => khoadaotao::all(),
                'tkbs' => tkb::all(),
                'phonghocs' => phonghoc::all(),
            ];
            return view('schedules.admin.schedules', $data);
        }
        return Redirect::to('')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }
    public function saveSchedule(Request $request)
    {
        if (session()->has('user')) {
            $request->validate([
                'KhoaDaoTao' => 'required|string',
                'ChuongTrinhTrienKhai' => 'required|string',
                'HocKi' => 'required|string',
                'Lop' => 'required|string',
                'NgayHoc' => 'required|date',
                'PhongHoc' => 'required|string',
            ], [
                'KhoaDaoTao.required' => 'Hãy chọn khoá đào tạo!',
                'ChuongTrinhTrienKhai.required' => 'Hãy chọn chương trình triển khai!',
                'HocKi.required' => 'Hãy chọn học kỳ!',
                'NgayHoc.required' => 'Hãy chọn ngày bắt đầu học!',
                'Lop.required' => 'Hãy chọn lớp!',
                'PhongHoc.required' => 'Hãy chọn phòng học!',
            ]);

            $hocki = hocki::where('MaHK', $request->input('HocKi'))->first();
            $scheduleName = 'THỜI KHÓA BIỂU LỚP ' . $request->input('Lop') . ' - ' . $hocki->TenHK . ' (' . $request->input('ChuongTrinhTrienKhai') . ')';

            $existingSchedule = tkb::where('TenTKB', $scheduleName)->first();
            if ($existingSchedule) {
                return redirect()->back()->withInput()->with('error', 'Thời khóa biểu với thông tin lớp, học kỳ và chương trình này đã tồn tại!');
            }

            $schedule = new tkb([
                'TenTKB' => $scheduleName,
                'MaLop' => $request->input('Lop'),
                'MaHK' => $request->input('HocKi'),
                'NgayHoc' => $request->input('NgayHoc'),
                'ngayHocType' => $request->input('ngayHocType', 'all'), // Thêm dòng này
            ]);
            $schedule->save();

            danhsachphong::updateOrCreate(
                [
                    'MaLop' => $schedule->MaLop,
                    'NgaySuDung' => $schedule->NgayHoc,
                ],
                [
                    'TenPhong' => $request->input('PhongHoc'),
                    'TrangThai' => 'Đang sử dụng'
                ]
            );

            return redirect()->route('schedules')->with('success', 'Tạo thời khóa biểu thành công!');
        }
        return Redirect::to('')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }
    public function scheduleDetail($TenTKB)
    {
        if (session()->has('user')) {
            $schedule = tkb::find($TenTKB);

            if ($schedule === null) {
                return redirect()->route('schedules')->with('error', 'Không tìm thấy thời khóa biểu.');
            }

            $lophoc = lophoc::find($schedule->MaLop);
            $chuongtrinh = chuongtrinh::with('khoadaotao')->find($lophoc->MaChuongTrinh);

            $khoaDaoTaoName = $chuongtrinh && $chuongtrinh->khoadaotao ? $chuongtrinh->khoadaotao->TenKhoaDaoTao : 'Chưa xác định';
            $chuongTrinhName = $chuongtrinh ? $chuongtrinh->TenChuongTrinh : 'Chưa xác định';

            $phonglt = danhsachphong::where('MaLop', $lophoc->MaLop)->where('TenPhong', 'LIKE', '%Class%')->first();
            $phongth = danhsachphong::where('MaLop', $lophoc->MaLop)->where('TenPhong', 'LIKE', '%Lab%')->first();
            $hocki = hocki::find($schedule->MaHK);

            $dsmh = danhsachmonhoc::where('MaHK', $hocki->MaHK)->first();
            // Retrieve danhsachngaynghi records and load the related ngaynghi objects
            $danhsachngaynghiRecords = danhsachngaynghi::with('ngayNghi')->where('TenTKB', $TenTKB)->get();
            // Extract the ngaynghi objects
            $ngaynghis = $danhsachngaynghiRecords->pluck('ngayNghi')->filter()->values(); // filter() removes nulls, values() re-indexes
            $monhocs = danhsachmonhoc::where('MaHK', $hocki->MaHK)->get();
            $khunggio = khunggio::all();
            $ngaytuhocs = ngaytuhoc::where('TenTKB', $schedule->TenTKB)->get();

            // Lấy tất cả các phòng đã gán cho lớp này đúng ngày sử dụng (ngày bắt đầu học của TKB)
            $phongs = danhsachphong::where('MaLop', $lophoc->MaLop)
                ->where('NgaySuDung', $schedule->NgayHoc)
                ->get();

            // Ngày bắt đầu học
            $startDate = Carbon::parse($schedule->NgayHoc);
            $totalHours = $hocki->TongGioTrienKhai;
            $emptyDays = 0;
            // Xác định ngày đầu tuần (Thứ 2) của tuần chứa ngày bắt đầu học
            $weekStartDate = $startDate->copy()->startOfWeek();
            // Đếm số ngày trống trước ngày bắt đầu học trong tuần đó (không tính thứ 7 và Chủ nhật)
            for ($date = $weekStartDate->copy(); $date->lt($startDate); $date->addDay()) {
                if ($date->dayOfWeek !== Carbon::SATURDAY && $date->dayOfWeek !== Carbon::SUNDAY) {
                    $emptyDays++;
                }
            }
            // Cộng số ngày trống vào tổng thời gian (2 giờ cho mỗi ngày trống)
            $totalHours += $emptyDays * 2;
            // Tính tổng số giờ học và tổng số tuần (chỉ tính 20 giờ/tuần nếu không có ngày nghỉ hay tự học)
            $totalWeeks = ceil($totalHours / 20);

            //lọc môn học có giờ triển khai nhiều hơn 0
            $filteredMonHocs = [];
            foreach ($monhocs as $index => $monhoc) {
                if ($monhoc && $monhoc->GioTrienKhai > 0) {
                    $filteredMonHocs[] = $monhoc;
                }
            }

            // Đếm số môn học
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

            // Hàm thêm ngày bỏ qua cuối tuần
            $addDaysSkippingWeekends = function ($date, $days) {
                while ($days > 0) {
                    $date->addDay();
                    if ($date->dayOfWeek !== Carbon::SATURDAY && $date->dayOfWeek !== Carbon::SUNDAY) {
                        $days--;
                    }
                }
                return $date;
            };

            // Xử lý các ngày tự học
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

            // Xử lý các ngày nghỉ
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

            // Hàm lấy thông tin giảng viên cho môn học
            $getTeacherInfo = function ($maMH) use ($schedule) {
                if (!isset($schedule) || !$schedule) {
                    return ' [NO SCHEDULE]';
                }
                $giangDay = GiangDay::where('MaMH', $maMH)->where('MaLop', $schedule->MaLop)->first();
                if (!$giangDay) {
                    return ' [NO GIANGDAY]';
                }
                $giangVien = giaovien::where('MaGV', $giangDay->MaGV)->first();
                return $giangVien ? ' - GV: ' . $giangVien->HoTenGV : ' [NO GIANGVIEN]';
            };

            $examCounter = 0;

            // Hàm lấy môn học cho ngày hiện tại (Crucial for building scheduleMatrix)
            $getSubjectForDay = function (&$subjectOccurrences, $currentDate, &$totalHours, &$examDays, $addDaysSkippingWeekends, $holidayDates, &$examCounter) use ($schedule) {
                foreach ($subjectOccurrences as $subject => &$details) {
                    if ($details['remaining'] > 0) {
                        if (is_null($details['first'])) {
                            $details['first'] = $currentDate;
                        }
                        $details['remaining'] -= 4;
                        if ($details['remaining'] <= 0) {
                            $details['last'] = $currentDate;
                            if (isset($details['lastSubject']) && $details['lastSubject']) {
                                // Xử lý môn học cuối cùng
                                $examDate = Carbon::parse($currentDate->format('Y-m-d'))->addWeek();
                                // Kiểm tra và điều chỉnh nếu ngày thi trùng với ngày nghỉ
                                while (
                                    isset($holidayDates[$examDate->format('Y-m-d')])
                                ) {
                                    $examDate->addDay();
                                }
                            } else {
                                // Xử lý các môn học khác
                                $examDate = Carbon::parse($currentDate->format('Y-m-d'))->addWeek();
                                // Kiểm tra và điều chỉnh nếu ngày thi trùng với ngày nghỉ
                                while (
                                    isset($holidayDates[$examDate->format('Y-m-d')])
                                ) {
                                    $examDate->addDay();
                                }
                            }
                            $examCounter++;
                            $examDays[$examDate->format('Y-m-d')] = [
                                'subject_string' => 'Thi ' . $subjectOccurrences[$subject]['TenMH'] . " ($subject, E$examCounter) - L",
                                'MaMH' => $subject, // Store the actual MaMH here
                            ];
                            $totalHours += 2;
                            Log::debug('Exam Day Calculation in PagesController: ' . json_encode([
                                'currentDate' => $currentDate->format('Y-m-d'),
                                'examDate' => $examDate->format('Y-m-d'),
                                'subject_code' => $subject, // This is the MaMH
                                'full_exam_string' => $examDays[$examDate->format('Y-m-d')]['subject_string'],
                            ], JSON_UNESCAPED_UNICODE));
                        }
                        return $subject;
                    }
                }
                return '';
            };

            // Tạo lịch học
            $scheduleMatrix = [];
            $examDays = []; // Ensure $examDays is re-initialized for this context
            for ($week = 1; $week <= $totalWeeks; $week++) {
                $weekStart = $startDate
                    ->copy()
                    ->addWeeks($week - 1)
                    ->startOfWeek();
                $scheduleMatrix[$week] = [];
                foreach ($weekDays as $dayIndex => $day) {
                    $currentDate = $weekStart->copy()->addDays($dayIndex);
                    $subject = '';
                    $inlineStyle = '';
                    $cssClasses = '';

                    if ($currentDate->gte($startDate)) {
                        if (isset($examDays[$currentDate->format('Y-m-d')])) {
                            $subject = $examDays[$currentDate->format('Y-m-d')]['subject_string'];
                            if (strpos($subject, 'self-study') !== false) {
                                $inlineStyle = 'color: black;';
                                $cssClasses = 'event-self-study';
                            } else {
                                $inlineStyle = 'color: blue; font-weight: bold;';
                                $cssClasses = 'event-exam';
                            }
                        } elseif (isset($selfStudyDays[$currentDate->format('Y-m-d')])) {
                            $subject = $selfStudyDays[$currentDate->format('Y-m-d')];
                            $inlineStyle = 'color: green; font-weight: bold;';
                            $cssClasses = 'event-self-study';
                        } else {
                            if (isset($holidayDates[$currentDate->format('Y-m-d')])) {
                                $subject = $holidayDates[$currentDate->format('Y-m-d')];
                                $inlineStyle = 'background-color: yellow;';
                                $cssClasses = 'event-holiday';
                            } else {
                                $subject = $getSubjectForDay(
                                    $subjectOccurrences,
                                    $currentDate,
                                    $totalHours,
                                    $examDays,
                                    $addDaysSkippingWeekends,
                                    $holidayDates,
                                    $examCounter
                                );
                                if ($subject) {
                                    if (isset($subjectOccurrences[$subject]['first']) && $subjectOccurrences[$subject]['first']->eq($currentDate)) {
                                        $inlineStyle = 'color: red; font-weight: bold;';
                                        $cssClasses = 'event-start';
                                    } elseif (isset($subjectOccurrences[$subject]['last']) && $subjectOccurrences[$subject]['last']->eq($currentDate)) {
                                        $inlineStyle = 'color: purple; font-weight: bold;';
                                        $cssClasses = 'event-end';
                                    }
                                }
                            }
                        }
                    } else {
                        // Ngày trước ngày bắt đầu học
                        $subject = '';
                        $inlineStyle = '';
                        $cssClasses = '';
                    }
                    $scheduleMatrix[$week][$day] = [
                        'date' => $currentDate->format('d/m/Y'),
                        'subject' => $subject,
                        'style' => $inlineStyle,
                        'class' => $cssClasses,
                        'MaMH' => null, // Initialize
                        'is_exam' => false, // Initialize
                        'is_holiday' => isset($holidayDates[$currentDate->format('Y-m-d')]),
                        'is_self_study_day' => isset($selfStudyDays[$currentDate->format('Y-m-d')]),
                    ];

                    // Determine MaMH and is_exam after subject is set
                    if (isset($examDays[$currentDate->format('Y-m-d')])) {
                        $scheduleMatrix[$week][$day]['MaMH'] = $examDays[$currentDate->format('Y-m-d')]['MaMH'];
                        $scheduleMatrix[$week][$day]['is_exam'] = true;
                    } elseif ($subject && isset($subjectOccurrences[$subject])) {
                        $scheduleMatrix[$week][$day]['MaMH'] = $subject;
                    }
                }
            }

            return view('schedules.admin.schedule_detail', compact(
                'schedule',
                'chuongtrinh',
                'phonglt',
                'phongth',
                'hocki',
                'dsmh',
                'ngaynghis',
                'monhocs',
                'khunggio',
                'ngaytuhocs',
                'khoaDaoTaoName',
                'chuongTrinhName',
                'scheduleMatrix',
                'startDate',
                'totalWeeks',
                'weekDays',
                'examDays',
                'subjectOccurrences',
                'phongs'
            ));
        }
        return Redirect::to('')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }
    public function teacherSchedule($TenTKB)
    {
        if (session()->has('user')) {
            $schedule = tkb::where('TenTKB', $TenTKB)->first();

            if (!$schedule) {
                return redirect()->back()->with('error', 'Không tìm thấy thời khóa biểu.');
            }

            $lophoc = lophoc::find($schedule->MaLop);
            $chuongtrinh = chuongtrinh::find($lophoc->MaChuongTrinh ?? null);

            $chuongTrinhName = $chuongtrinh ? $chuongtrinh->TenChuongTrinh : 'Chưa xác định';

            // Tìm phòng lý thuyết và thực hành từ danhsachphong dựa trên MaLop của TKB
            $phonglt = danhsachphong::where('MaLop', $schedule->MaLop)->where('TenPhong', 'LIKE', '%Class%')->first();
            $phongth = danhsachphong::where('MaLop', $schedule->MaLop)->where('TenPhong', 'LIKE', '%Lab%')->first();

            // Lấy thông tin giảng dạy của các môn học
            $giangDays = GiangDay::where('MaLop', $schedule->MaLop)
                ->with(['monhoc', 'giaovien'])
                ->get()
                ->groupBy('MaGV');

            // Lấy thông tin các ngày nghỉ
            $danhsachngaynghiRecords = danhsachngaynghi::with('ngayNghi')->where('TenTKB', $TenTKB)->get();
            $ngaynghis = $danhsachngaynghiRecords->pluck('ngayNghi')->filter()->values();

            // Lấy thông tin các ngày tự học
            $ngaytuhocs = ngaytuhoc::where('TenTKB', $TenTKB)->get();

            // Xử lý các ngày tự học (tương tự như trong phương thức schedule)
            $selfStudyDays = [];
            foreach ($ngaytuhocs as $ngaytuhoc) {
                $selfStudyStart = Carbon::parse($ngaytuhoc->NgayBDTuHoc);
                $selfStudyEnd = Carbon::parse($ngaytuhoc->NgayKTTuHoc);
                while ($selfStudyStart->lte($selfStudyEnd)) {
                    if ($selfStudyStart->dayOfWeek !== Carbon::SATURDAY && $selfStudyStart->dayOfWeek !== Carbon::SUNDAY) {
                        $selfStudyDays[$selfStudyStart->format('Y-m-d')] = $ngaytuhoc->TenNgayTuHoc;
                    }
                    $selfStudyStart->addDay();
                }
            }

            // Xử lý các ngày nghỉ (tương tự như trong phương thức schedule)
            $holidayDates = [];
            foreach ($ngaynghis as $ngaynghi) {
                $holidayStart = Carbon::parse($ngaynghi->NgayBDNghi);
                $holidayEnd = Carbon::parse($ngaynghi->NgayKT);
                while ($holidayStart->lte($holidayEnd)) {
                    if ($holidayStart->dayOfWeek !== Carbon::SATURDAY && $holidayStart->dayOfWeek !== Carbon::SUNDAY) {
                        $holidayDates[$holidayStart->format('Y-m-d')] = $ngaynghi->TenNgayNghi;
                    }
                    $holidayStart->addDay();
                }
            }

            // Lấy thông tin học kỳ
            $hocki = hocki::find($schedule->MaHK);
            $dsmh = danhsachmonhoc::where('MaHK', $hocki->MaHK)->first();

            // Ngày bắt đầu học
            $startDate = Carbon::parse($schedule->NgayHoc);
            $totalHours = $hocki->TongGioTrienKhai;
            $emptyDays = 0;
            // Xác định ngày đầu tuần (Thứ 2) của tuần chứa ngày bắt đầu học
            $weekStartDate = $startDate->copy()->startOfWeek();
            // Đếm số ngày trống trước ngày bắt đầu học trong tuần đó (không tính thứ 7 và Chủ nhật)
            for ($date = $weekStartDate->copy(); $date->lt($startDate); $date->addDay()) {
                if ($date->dayOfWeek !== Carbon::SATURDAY && $date->dayOfWeek !== Carbon::SUNDAY) {
                    $emptyDays++;
                }
            }
            // Cộng số ngày trống vào tổng thời gian (2 giờ cho mỗi ngày trống)
            $totalHours += $emptyDays * 2;
            // Tính tổng số giờ học và tổng số tuần
            $totalWeeks = ceil($totalHours / 20);

            // Retrieve monhocs for schedule generation
            $monhocs = danhsachmonhoc::where('MaHK', $hocki->MaHK)->get();

            //lọc môn học có giờ triển khai nhiều hơn 0
            $filteredMonHocs = [];
            foreach ($monhocs as $index => $monhoc) {
                if ($monhoc && $monhoc->GioTrienKhai > 0) {
                    $filteredMonHocs[] = $monhoc;
                }
            }
            // Đếm số môn học
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
            // Hàm thêm ngày bỏ qua cuối tuần
            $addDaysSkippingWeekends = function ($date, $days) {
                while ($days > 0) {
                    $date->addDay();
                    if ($date->dayOfWeek !== Carbon::SATURDAY && $date->dayOfWeek !== Carbon::SUNDAY) {
                        $days--;
                    }
                }
                return $date;
            };

            // Hàm lấy môn học cho ngày hiện tại (Crucial for building scheduleMatrix)
            $examCounter = 0; // Initialize here for clarity

            $getSubjectForDay = function (&$subjectOccurrences, $currentDate, &$totalHours, &$examDays, $addDaysSkippingWeekends, $holidayDates, &$examCounter) use ($schedule) {
                foreach ($subjectOccurrences as $subject => &$details) {
                    if ($details['remaining'] > 0) {
                        if (is_null($details['first'])) {
                            $details['first'] = $currentDate;
                        }
                        $details['remaining'] -= 4;
                        if ($details['remaining'] <= 0) {
                            $details['last'] = $currentDate;
                            if (isset($details['lastSubject']) && $details['lastSubject']) {
                                // Xử lý môn học cuối cùng
                                $examDate = Carbon::parse($currentDate->format('Y-m-d'))->addWeek();
                                // Kiểm tra và điều chỉnh nếu ngày thi trùng với ngày nghỉ
                                while (
                                    isset($holidayDates[$examDate->format('Y-m-d')])
                                ) {
                                    $examDate->addDay();
                                }
                            } else {
                                // Xử lý các môn học khác
                                $examDate = Carbon::parse($currentDate->format('Y-m-d'))->addWeek();
                                // Kiểm tra và điều chỉnh nếu ngày thi trùng với ngày nghỉ
                                while (
                                    isset($holidayDates[$examDate->format('Y-m-d')])
                                ) {
                                    $examDate->addDay();
                                }
                            }
                            $examCounter++;
                            $examDays[$examDate->format('Y-m-d')] = [
                                'subject_string' => 'Thi ' . $subjectOccurrences[$subject]['TenMH'] . " ($subject, E$examCounter) - L",
                                'MaMH' => $subject, // Store the actual MaMH here
                            ];
                            $totalHours += 2;
                            Log::debug('Exam Day Calculation in PagesController: ' . json_encode([
                                'currentDate' => $currentDate->format('Y-m-d'),
                                'examDate' => $examDate->format('Y-m-d'),
                                'subject_code' => $subject, // This is the MaMH
                                'full_exam_string' => $examDays[$examDate->format('Y-m-d')]['subject_string'],
                            ], JSON_UNESCAPED_UNICODE));
                        }
                        return $subject;
                    }
                }
                return '';
            };

            // Tạo lịch học
            $scheduleMatrix = [];
            $examDays = []; // Re-initialize for this context
            // Map tên thứ sang số thứ trong tuần (Carbon: 0=Chủ nhật, 1=Thứ 2, ..., 6=Thứ 7)
            $dayOfWeekMap = [
                'THỨ HAI' => 1,
                'THỨ BA' => 2,
                'THỨ TƯ' => 3,
                'THỨ NĂM' => 4,
                'THỨ SÁU' => 5,
                'THỨ BẢY' => 6,
            ];
            for ($week = 1; $week <= $totalWeeks; $week++) {
                $weekStart = $startDate
                    ->copy()
                    ->addWeeks($week - 1)
                    ->startOfWeek(); // luôn là thứ 2
                $scheduleMatrix[$week] = [];
                foreach ($weekDays as $day) {
                    // Tính offset từ thứ 2 đến đúng thứ cần xét
                    $offset = $dayOfWeekMap[$day] - 1; // Thứ 2 - 1 = 0, Thứ 3 - 1 = 1, ...
                    $currentDate = $weekStart->copy()->addDays($offset);
                    $subject = '';
                    $inlineStyle = '';
                    $cssClasses = '';

                    if ($currentDate->gte($startDate)) {
                        if (isset($examDays[$currentDate->format('Y-m-d')])) {
                            $subject = $examDays[$currentDate->format('Y-m-d')]['subject_string'];
                            if (strpos($subject, 'self-study') !== false) {
                                $inlineStyle = 'color: black;';
                                $cssClasses = 'event-self-study';
                            } else {
                                $inlineStyle = 'color: blue; font-weight: bold;';
                                $cssClasses = 'event-exam';
                            }
                        } elseif (isset($selfStudyDays[$currentDate->format('Y-m-d')])) {
                            $subject = $selfStudyDays[$currentDate->format('Y-m-d')];
                            $inlineStyle = 'color: green; font-weight: bold;';
                            $cssClasses = 'event-self-study';
                        } else {
                            if (isset($holidayDates[$currentDate->format('Y-m-d')])) {
                                $subject = $holidayDates[$currentDate->format('Y-m-d')];
                                $inlineStyle = 'background-color: yellow;';
                                $cssClasses = 'event-holiday';
                            } else {
                                $subject = $getSubjectForDay(
                                    $subjectOccurrences,
                                    $currentDate,
                                    $totalHours,
                                    $examDays,
                                    $addDaysSkippingWeekends,
                                    $holidayDates,
                                    $examCounter
                                );
                                if ($subject) {
                                    if (isset($subjectOccurrences[$subject]['first']) && $subjectOccurrences[$subject]['first']->eq($currentDate)) {
                                        $inlineStyle = 'color: red; font-weight: bold;';
                                        $cssClasses = 'event-start';
                                    } elseif (isset($subjectOccurrences[$subject]['last']) && $subjectOccurrences[$subject]['last']->eq($currentDate)) {
                                        $inlineStyle = 'color: purple; font-weight: bold;';
                                        $cssClasses = 'event-end';
                                    }
                                }
                            }
                        }
                    } else {
                        // Ngày trước ngày bắt đầu học
                        $subject = '';
                        $inlineStyle = '';
                        $cssClasses = '';
                    }
                    $scheduleMatrix[$week][$day] = [
                        'date' => $currentDate->format('d/m/Y'),
                        'subject' => $subject,
                        'style' => $inlineStyle,
                        'class' => $cssClasses,
                        'MaMH' => null, // Initialize
                        'is_exam' => false, // Initialize
                        'is_holiday' => isset($holidayDates[$currentDate->format('Y-m-d')]),
                        'is_self_study_day' => isset($selfStudyDays[$currentDate->format('Y-m-d')]),
                    ];

                    // Determine MaMH and is_exam after subject is set
                    if (isset($examDays[$currentDate->format('Y-m-d')])) {
                        $scheduleMatrix[$week][$day]['MaMH'] = $examDays[$currentDate->format('Y-m-d')]['MaMH'];
                        $scheduleMatrix[$week][$day]['is_exam'] = true;
                    } elseif ($subject && isset($subjectOccurrences[$subject])) {
                        $scheduleMatrix[$week][$day]['MaMH'] = $subject;
                    }
                }
            }

            // Lọc danh sách giáo viên chỉ lấy những người có môn học xuất hiện trong scheduleMatrix
            $actualMaMHs = [];
            foreach ($scheduleMatrix as $week) {
                foreach ($week as $day) {
                    if (!empty($day['MaMH'])) {
                        $actualMaMHs[] = $day['MaMH'];
                    }
                }
            }
            $actualMaMHs = array_unique($actualMaMHs);

            $teacherSchedules = [];
            $weekDaysFull = ['THỨ HAI', 'THỨ BA', 'THỨ TƯ', 'THỨ NĂM', 'THỨ SÁU', 'THỨ BẢY'];
            foreach ($giangDays as $maGV => $subjectsByGV) {
                // Chỉ lấy giáo viên có môn học xuất hiện trong scheduleMatrix
                if ($subjectsByGV->pluck('MaMH')->intersect($actualMaMHs)->isEmpty()) {
                    continue;
                }
                $teacherSchedules[$maGV] = [
                    'info' => $subjectsByGV->first()->giaovien,
                    'schedule' => []
                ];

                for ($week = 1; $week <= $totalWeeks; $week++) {
                    $teacherSchedules[$maGV]['schedule'][$week] = [];
                    foreach ($weekDaysFull as $dayName) {
                        $dayInfoFromScheduleMatrix = $scheduleMatrix[$week][$dayName] ?? [
                            'date' => '',
                            'subject' => '-',
                            'style' => '',
                            'class' => '',
                            'MaMH' => null,
                            'is_exam' => false,
                            'is_holiday' => false,
                            'is_self_study_day' => false,
                        ];

                        $teacherSubjectInfo = $dayInfoFromScheduleMatrix['subject'];
                        $teacherSubjectStyle = $dayInfoFromScheduleMatrix['style'];
                        $teacherSubjectClass = $dayInfoFromScheduleMatrix['class'];

                        $isExam = $dayInfoFromScheduleMatrix['is_exam'] ?? false;
                        $isHoliday = $dayInfoFromScheduleMatrix['is_holiday'] ?? false;
                        $isSelfStudy = $dayInfoFromScheduleMatrix['is_self_study_day'] ?? false;
                        $originalMaMH = $dayInfoFromScheduleMatrix['MaMH'] ?? null;

                        if ($isSelfStudy || (isset($examDays[$currentDate->format('Y-m-d')]) && $examDays[$currentDate->format('Y-m-d')] === 'self-study')) {
                            // Ngày self-study: tất cả giảng viên đều để trống
                            $teacherSubjectInfo = 'self-study';
                            $teacherSubjectStyle = 'color: black;';
                            $teacherSubjectClass = 'event-self-study';
                        } elseif ($isHoliday) {
                            // Ngày nghỉ: luôn hiển thị cho tất cả GV
                            $teacherSubjectInfo = $dayInfoFromScheduleMatrix['subject'];
                            $teacherSubjectStyle = $dayInfoFromScheduleMatrix['style'];
                            $teacherSubjectClass = $dayInfoFromScheduleMatrix['class'];
                        } elseif ($isExam) {
                            // Ngày thi: chỉ hiển thị nếu giáo viên này dạy môn thi đó
                            if ($originalMaMH && $subjectsByGV->pluck('MaMH')->contains($originalMaMH)) {
                                $teacherSubjectInfo = $dayInfoFromScheduleMatrix['subject'];
                            } else {
                                $teacherSubjectInfo = '-';
                                $teacherSubjectStyle = '';
                                $teacherSubjectClass = '';
                            }
                        } elseif ($originalMaMH) {
                            // Môn học: chỉ hiển thị nếu đúng môn của giảng viên
                            $assignedGiangDay = $subjectsByGV->where('MaMH', $originalMaMH)->first();
                            if ($assignedGiangDay) {
                                $teacherSubjectInfo = $assignedGiangDay->monhoc->TenMH . ' - GV: ' . $assignedGiangDay->giaovien->HoTenGV;
                            } else {
                                $teacherSubjectInfo = '-';
                                $teacherSubjectStyle = '';
                                $teacherSubjectClass = '';
                            }
                        } else {
                            // Ngày trống
                            $teacherSubjectInfo = '-';
                            $teacherSubjectStyle = '';
                            $teacherSubjectClass = '';
                        }

                        // Luôn giữ nguyên cấu trúc ngày/tuần như scheduleMatrix
                        $teacherSchedules[$maGV]['schedule'][$week][$dayName] = [
                            'date' => $dayInfoFromScheduleMatrix['date'],
                            'subject' => $teacherSubjectInfo,
                            'style' => $teacherSubjectStyle,
                            'class' => $teacherSubjectClass,
                            'is_exam' => $isExam,
                            'MaMH' => $originalMaMH,
                        ];
                    }
                }
            }
            \Log::debug('Teacher Schedule Debug - Exam Days:', $examDays);
            \Log::debug('Teacher Schedule Debug - Self-Study Days:', $selfStudyDays);

            // --- BẮT ĐẦU ĐOẠN MÃ ĐỂ DEBUG ---
            \Log::debug('Final Schedule Matrix before passing to view:', $scheduleMatrix);
            for ($week = 1; $week <= $totalWeeks; $week++) {
                $mondayDate = $scheduleMatrix[$week]['THỨ HAI']['date'] ?? 'N/A';
                $saturdayDate = $scheduleMatrix[$week]['THỨ BẢY']['date'] ?? 'N/A';
                \Log::debug("Schedule Matrix - Week {$week}: Monday: {$mondayDate}, Saturday: {$saturdayDate}");
            }
            // --- KẾT THÚC ĐOẠN MÃ ĐỂ DEBUG ---

            return view('schedules.admin.teacher_schedule', compact(
                'schedule',
                'teacherSchedules',
                'hocki',
                'chuongTrinhName',
                'phonglt',
                'phongth',
                'startDate',
                'totalWeeks',
                'weekDays',
                'dsmh',
                'scheduleMatrix',
                'examDays'
            ));
        }
        return Redirect::to('')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }
    public function deleteSchedule($TenTKB)
    {
        if (session()->has('user')) {
            $schedule = tkb::where('TenTKB', $TenTKB)->first();
            if ($schedule) {
                // Delete related records in danhsachngaynghi first
                \App\Models\danhsachngaynghi::where('TenTKB', $TenTKB)->delete();

                // Xóa các bản ghi phòng học liên quan đến lớp/ngày này
                \App\Models\danhsachphong::where('MaLop', $schedule->MaLop)
                    ->where('NgaySuDung', $schedule->NgayHoc)
                    ->delete();

                // Now delete the tkb record
                $schedule->delete();

                return redirect()->route('schedules')->with('success', 'Thời khóa biểu đã được xóa.');
            } else {
                return redirect()->route('schedules')->with('error', 'Không tìm thấy thời khóa biểu với tên đã cung cấp.');
            }
        }
        return Redirect::to('')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }
    public function exportExcel($TenTKB)
    {
        if (session()->has('user')) {
            $schedule = tkb::where('TenTKB', $TenTKB)->first();
            $lophoc = lophoc::where('MaLop', $schedule->MaLop)->first();
            $chuongtrinh = chuongtrinh::where('MaChuongTrinh', $lophoc->MaChuongTrinh)->first();
            $phonglt = danhsachphong::where('MaLop', $lophoc->MaLop)->where('TenPhong', 'LIKE', '%Class%')->first();
            $phongth = danhsachphong::where('MaLop', $lophoc->MaLop)->where('TenPhong', 'LIKE', '%Lab%')->first();
            $hocki = hocki::where('MaHK', $schedule->MaHK)->first();
            $dsmh = danhsachmonhoc::where('MaHK', $hocki->MaHK)->first();
            $ngaynghis = danhsachngaynghi::where('TenTKB', $TenTKB)->get()->pluck('ngayNghi');
            $monhocs = danhsachmonhoc::where('MaHK', $hocki->MaHK)->get()->pluck('monhoc');
            $ngaytuhocs = ngaytuhoc::where('TenTKB', $schedule->TenTKB)->get();

            return Excel::download(new ScheduleExport($schedule, $chuongtrinh, $phonglt, $phongth, $dsmh, $hocki, $ngaynghis, $monhocs, $ngaytuhocs), 'schedule.xlsx');
        }
        return Redirect::to('')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }
    public function saveholiday(Request $request, $TenTKB)
    {
        if (session()->has('user')) {
            $request->validate([
                'TenNgayNghi' => 'required|string',
                'NgayBDNghi' => 'required|date',
                'NgayKT' => 'required|date|after_or_equal:NgayBDNghi',
            ]);

            try {
                // Tạo ngày nghỉ mới
                $ngaynghi = new ngaynghi([
                    'TenNgayNghi' => $request->input('TenNgayNghi'),
                    'NgayBDNghi' => $request->input('NgayBDNghi'),
                    'NgayKT' => $request->input('NgayKT'),
                ]);
                $ngaynghi->save();

                // Tạo liên kết trong danhsachngaynghi
                $danhsachngaynghi = new danhsachngaynghi([
                    'TenTKB' => $TenTKB,
                    'MaNgayNghi' => $ngaynghi->MaNgayNghi
                ]);
                $danhsachngaynghi->save();

                return redirect()->route('schedule', ['TenTKB' => $TenTKB])
                    ->with('success', 'Thêm ngày nghỉ thành công!');
            } catch (Exception $e) {
                return redirect()->back()
                    ->with('error', 'Có lỗi xảy ra khi thêm ngày nghỉ: ' . $e->getMessage())
                    ->withInput();
            }
        }
        return Redirect::to('')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }
    public function getSubjects()
    {
        if (session()->has('user')) {
            $subjects = monhoc::all();
            return response()->json($subjects);
        }
        return Redirect::to('')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }
    public function updateScheduleSubjects(Request $request, $TenTKB)
    {
        if (session()->has('user')) {
            $selectedSubjects = $request->input('subjects');
            $schedule = tkb::where('TenTKB', $TenTKB)->first();
            $maHK = $schedule->MaHK;

            // Ghi log để debug
            \Log::info('Đang cập nhật môn học cho lịch', [
                'TenTKB' => $TenTKB,
                'MaHK' => $maHK,
                'selectedSubjects' => $selectedSubjects
            ]);

            // Xóa các môn học cũ
            danhsachmonhoc::where('MaHK', $maHK)->delete();

            // Lưu môn học mới
            if (!empty($selectedSubjects)) {
                foreach ($selectedSubjects as $subject) {
                    $created = danhsachmonhoc::create([
                        'MaHK' => $maHK,
                        'MaMH' => $subject['MaMH'],
                        'TenMH' => $subject['TenMH'],
                        'GioTrienKhai' => $subject['GioTrienKhai'],
                    ]);

                    \Log::info('Đã tạo bản ghi môn học', [
                        'subject' => $created->toArray()
                    ]);
                }
            }

            return redirect()->route('schedule', ['TenTKB' => $TenTKB])
                ->with('success', 'Môn học đã được cập nhật cho thời khóa biểu.');
        }
        return Redirect::to('')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }
    public function saveSelfStudy(Request $request, $TenTKB)
    {
        if (session()->has('user')) {
            $request->validate([
                'TenNgayTuHoc' => 'required|string',
                'NgayBDTuHoc' => 'required|date',
                'NgayKTTuHoc' => 'required|date|after_or_equal:NgayBDTuHoc',
            ]);

            $ngaytuhoc = new ngaytuhoc([
                'TenNgayTuHoc' => $request->input('TenNgayTuHoc'),
                'NgayBDTuHoc' => $request->input('NgayBDTuHoc'),
                'NgayKTTuHoc' => $request->input('NgayKTTuHoc'),
                'TenTKB' => $TenTKB
            ]);
            $ngaytuhoc->save();

            return redirect()->route('schedule', ['TenTKB' => $TenTKB])
                ->with('success', 'Thêm ngày tự học thành công!');
        }
        return Redirect::to('')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }
}
