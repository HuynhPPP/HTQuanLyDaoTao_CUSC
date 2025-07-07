<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ScheduleExport;
use Exception;
use App\Models\khoadaotao;
use App\Models\chuongtrinh;
use App\Models\lophoc;
use App\Models\phonghoc;
use App\Models\tkb;
use App\Models\monhoc;
use App\Models\ngaynghi;
use App\Models\danhsachngaynghi;
use App\Models\TapHuan;
use App\Models\hocki;
use App\Models\khunggio;
use App\Models\danhsachphong;
use App\Models\danhsachmonhoc;
use App\Models\ngaytuhoc;
use App\Models\GiangDay;
use App\Models\giaovien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PagesController extends Controller
{
    public function index()
    {
        $functions = [
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập thời khóa biểu',
                'link' => route('schedules'),
                'color' => 'bg-info',
            ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập lịch Theo dõi phòng học',
                'link' => '',
                'color' => 'bg-info',
            ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập lịch theo dõi môn học sắp bắt đầu',
                'link' => '',
                'color' => 'bg-info',
            ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập điểm danh',
                'link' => '',
                'color' => 'bg-info',
            ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập lịch thi theo lớp',
                'link' => route('lichthi.index'),
                'color' => 'bg-info',
            ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập phân công thi',
                'link' => route('phancong.index'),
                'color' => 'bg-info',
            ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập lịch báo cáo đồ án',
                'link' => '#',
                'color' => 'bg-info',
            ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập bảng thống kê báo cáo đồ án',
                'link' => '#',
                'color' => 'bg-info',
            ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập bảng điểm chi tiết',
                'link' => route('lapbangdiemchitiet.chon-lop-mon-hoc'),
                'color' => 'bg-info',
            ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Thống kê kết quả học tập',
                'link' => route('chon-chuong-trinh-bang-diem-tong'),
                'color' => 'bg-info',
            ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập bảng báo cáo kết quả học tập',
                'link' => route('thong-ke.thong-ke-hoc-luc'),
                'color' => 'bg-info',
            ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập danh sách xét tốt nghiệp',
                'link' => '#',
                'color' => 'bg-info',
            ],
            // [
            //     'icon' => 'far fa-newspaper',
            //     'text' => 'Xuất điểm nhập điểm',
            //     'link' => route('bangdiem.chon'),
            //     'color' => 'bg-info',
            // ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập danh sách đề nghị ra quyết định công nhận tốt nghiệp',
                'link' => '#',
                'color' => 'bg-info',
            ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập nhật ký phát bằng',
                'link' => '#',
                'color' => 'bg-info',
            ],
        ];
        return view('main_sidebar.main_scheduling_system', compact('functions'));
    }
    public function about()
    {
        return view('main_sidebar.about');
    }

    public function login()
    {
        if (session()->has('user')) {
            return redirect('/');
        }

        return view('login', ['captchaUrl' => route('captcha')]);
    }

    public function getChuongTrinh($TenKhoaDaoTao)
    {
        if (session()->has('user')) {
            return response()->json(chuongtrinh::where('TenKhoaDaoTao', $TenKhoaDaoTao)->get());
        }
        return Redirect::to('')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }

    public function getLop($MaChuongTrinh)
    {
        if (session()->has('user')) {
            return response()->json(lophoc::where('MaChuongTrinh', $MaChuongTrinh)->get());
        }
        return Redirect::to('')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }

    public function getHK($MaChuongTrinh)
    {
        if (session()->has('user')) {
            return response()->json(hocki::where('MaChuongTrinh', $MaChuongTrinh)->get());
        }
        return Redirect::to('')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }

    

    

    public function EditTKB(Request $request, $TenTKB)
    {
        if (session()->has('user')) {
            $request->validate([
                'NgayHoc' => [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) {
                        $dayOfWeek = date('N', strtotime($value));
                        if ($dayOfWeek == 6 || $dayOfWeek == 7) {
                            $fail('Ngày bắt đầu học không được là thứ 7 hoặc chủ nhật!');
                        }
                    }
                ],
            ], [
                'NgayHoc.required' => 'Hãy chọn ngày bắt đầu!',
            ]);

            try {
                DB::beginTransaction();

                $schedule = tkb::where('TenTKB', $TenTKB)->first();

                if (!$schedule) {
                    return redirect()->back()->with('error', 'Không tìm thấy thời khóa biểu.');
                }

                // Chuyển đổi ngày từ request thành định dạng Y-m-d
                $newDate = date('Y-m-d', strtotime($request->input('NgayHoc')));

                // Cập nhật ngày học
                $schedule->NgayHoc = $newDate;
                $schedule->ngayHocType = $request->input('ngayHocType', 'all'); // Thêm dòng này
                $schedule->save();

                // Cập nhật lại ngày sử dụng cho các phòng học của lớp này
                $phongRecords = \App\Models\danhsachphong::where('MaLop', $schedule->MaLop)->get();
                foreach ($phongRecords as $phong) {
                    $phong->NgaySuDung = $schedule->NgayHoc;
                    $phong->save();
                }

                // Xóa tất cả các bản ghi liên quan để tạo lại TKB
                danhsachngaynghi::where('TenTKB', $TenTKB)->delete();
                ngaytuhoc::where('TenTKB', $TenTKB)->delete();

                DB::commit();

                // Thêm header để ngăn cache trình duyệt
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Pragma: no-cache");
                header("Expires: 0");

                return redirect()->route('schedule', ['TenTKB' => $TenTKB])
                    ->with('success', 'Cập nhật ngày khai giảng thành công!')
                    ->with('reload_timestamp', time()); // Giữ lại timestamp này phòng trường hợp cần
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()
                    ->with('error', 'Có lỗi xảy ra khi cập nhật ngày khai giảng: ' . $e->getMessage())
                    ->withInput();
            }
        }
        return Redirect::to('')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }

    public function saveTimeSlot(Request $request, $TenTKB)
    {
        if (session()->has('user')) {
            try {
                $request->validate([
                    'TenKhungGio' => 'required|string|max:255',
                    'GioBD' => 'required|date_format:H:i',
                    'GioKT' => 'required|date_format:H:i|after:GioBD',
                ], [
                    'TenKhungGio.required' => 'Hãy nhập tên khung giờ!',
                    'GioBD.required' => 'Hãy nhập giờ bắt đầu!',
                    'GioKT.required' => 'Hãy nhập giờ kết thúc!',
                    'GioKT.after' => 'Giờ kết thúc phải sau giờ bắt đầu!',
                ]);

                $schedule = tkb::where('TenTKB', $TenTKB)->first();

                if (!$schedule) {
                    return redirect()->back()->with('error', 'Không tìm thấy thời khóa biểu.');
                }

                $hocki = hocki::where('MaHK', $schedule->MaHK)->first();

                if (!$hocki) {
                    return redirect()->back()->with('error', 'Không tìm thấy học kỳ liên kết với thời khóa biểu.');
                }

                $tenKhungGio = $request->input('TenKhungGio');
                $gioBD = $request->input('GioBD');
                $gioKT = $request->input('GioKT');

                // Find or create the time slot in KhungGio table
                $khungGio = \App\Models\khunggio::updateOrCreate(
                    ['TenKhungGio' => $tenKhungGio],
                    ['ThoiGian' => $gioBD . ' - ' . $gioKT]
                );

                // Update or create the record in danhsachmonhoc
                $existingDSMH = danhsachmonhoc::where('MaHK', $hocki->MaHK)->first();
                if ($existingDSMH) {
                    $existingDSMH->update(['TenKhungGio' => $tenKhungGio]);
                } else {
                    danhsachmonhoc::create([
                        'MaHK' => $hocki->MaHK,
                        'TenKhungGio' => $tenKhungGio
                    ]);
                }

                // Lấy thông tin lớp học, phòng lý thuyết và phòng thực hành từ bảng lophoc và tkb
                $lophoc = lophoc::find($schedule->MaLop);
                $ngayHoc = $schedule->NgayHoc; // Ngày học của thời khóa biểu

                // Tìm và cập nhật phòng lý thuyết
                $phongltRecord = danhsachphong::where('MaLop', $lophoc->MaLop)->where('TenPhong', 'LIKE', '%Class%')->first();
                if ($phongltRecord) {
                    $phongltRecord->update([
                        'NgaySuDung' => $ngayHoc,
                        'Ca' => $khungGio->TenKhungGio, // Sử dụng TenKhungGio làm Ca
                        'TrangThai' => 'Đang sử dụng'
                    ]);
                    Log::info('Updated danhsachphong for phonglt', ['MaLop' => $lophoc->MaLop, 'TenPhong' => $phongltRecord->TenPhong, 'NgaySuDung' => $ngayHoc, 'Ca' => $khungGio->TenKhungGio]);
                } else {
                    Log::warning('Phong ly thuyet not found for MaLop', ['MaLop' => $lophoc->MaLop]);
                }

                // Tìm và cập nhật phòng thực hành
                $phongthRecord = danhsachphong::where('MaLop', $lophoc->MaLop)->where('TenPhong', 'LIKE', '%Lab%')->first();
                if ($phongthRecord) {
                    $phongthRecord->update([
                        'NgaySuDung' => $ngayHoc,
                        'Ca' => $khungGio->TenKhungGio, // Sử dụng TenKhungGio làm Ca
                        'TrangThai' => 'Đang sử dụng'
                    ]);
                    Log::info('Updated danhsachphong for phongth', ['MaLop' => $lophoc->MaLop, 'TenPhong' => $phongthRecord->TenPhong, 'NgaySuDung' => $ngayHoc, 'Ca' => $khungGio->TenKhungGio]);
                } else {
                    Log::warning('Phong thuc hanh not found for MaLop', ['MaLop' => $lophoc->MaLop]);
                }

                // Xóa các bản ghi phòng học khác của lớp/ngày này (chỉ giữ lại đúng 1 phòng học vừa cập nhật)
                $tenPhongCanGiu = $phongltRecord ? $phongltRecord->TenPhong : ($phongthRecord ? $phongthRecord->TenPhong : null);
                if ($tenPhongCanGiu) {
                    danhsachphong::where('MaLop', $lophoc->MaLop)
                        ->where('NgaySuDung', $ngayHoc)
                        ->where('TenPhong', '!=', $tenPhongCanGiu)
                        ->delete();
                }

                return redirect()->route('schedule', ['TenTKB' => $TenTKB])
                    ->with('success', 'Cập nhật khung giờ thành công!');

            } catch (\Illuminate\Validation\ValidationException $e) {
                return redirect()->back()
                    ->withErrors($e->errors())
                    ->withInput();
            } catch (Exception $e) {
                return redirect()->back()
                    ->with('error', 'Có lỗi xảy ra khi lưu khung giờ: ' . $e->getMessage());
            }
        }
        return Redirect::to('')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }

    public function monitorClassroom()
    {
        if (session()->has('user')) {
            return view('monitorClassroom', ['taphuans' => TapHuan::all()]);
        }
        return Redirect::to('')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }

    public function monitorSubject()
    {
        if (session()->has('user')) {
            return view('monitorSubject', ['taphuans' => TapHuan::all()]);
        }
        return Redirect::to('')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }

    public function rollCall()
    {
        if (session()->has('user')) {
            return view('rollCall', ['taphuans' => TapHuan::all()]);
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

    

    public function exportTeacherSchedule($TenTKB)
    {
        if (session()->has('user')) {
            $schedule = tkb::where('TenTKB', $TenTKB)->first();

            if (!$schedule) {
                return redirect()->back()->with('error', 'Không tìm thấy thời khóa biểu.');
            }

            $lophoc = lophoc::find($schedule->MaLop);
            $chuongtrinh = chuongtrinh::find($lophoc->MaChuongTrinh ?? null);

            $chuongTrinhName = $chuongtrinh ? $chuongtrinh->TenChuongTrinh : 'Chưa xác định';

            $phonglt = danhsachphong::where('MaLop', $schedule->MaLop)->where('TenPhong', 'LIKE', '%Class%')->first();
            $phongth = danhsachphong::where('MaLop', $schedule->MaLop)->where('TenPhong', 'LIKE', '%Lab%')->first();

            $giangDays = GiangDay::where('MaLop', $schedule->MaLop)
                ->with(['monhoc', 'giaovien'])
                ->get()
                ->groupBy('MaGV');

            $danhsachngaynghiRecords = danhsachngaynghi::with('ngayNghi')->where('TenTKB', $TenTKB)->get();
            $ngaynghis = $danhsachngaynghiRecords->pluck('ngayNghi')->filter()->values();
            $ngaytuhocs = ngaytuhoc::where('TenTKB', $TenTKB)->get();

            $hocki = hocki::find($schedule->MaHK);
            $dsmh = danhsachmonhoc::where('MaHK', $hocki->MaHK)->first();

            // Build teacherSchedules giống như ở view
            // --- BẮT ĐẦU ĐOẠN CODE LẤY $teacherSchedules ---
            // (Copy logic từ teacherSchedule method, chỉ lấy phần build $teacherSchedules)
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
            $monhocs = danhsachmonhoc::where('MaHK', $hocki->MaHK)->get();
            $filteredMonHocs = [];
            foreach ($monhocs as $index => $monhoc) {
                if ($monhoc && $monhoc->GioTrienKhai > 0) {
                    $filteredMonHocs[] = $monhoc;
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
            $weekDays = ['THỨ HAI', 'THỨ BA', 'THỨ TƯ', 'THỨ NĂM', 'THỨ SÁU', 'THỨ BẢY'];
            $addDaysSkippingWeekends = function ($date, $days) {
                while ($days > 0) {
                    $date->addDay();
                    if ($date->dayOfWeek !== Carbon::SATURDAY && $date->dayOfWeek !== Carbon::SUNDAY) {
                        $days--;
                    }
                }
                return $date;
            };
            $examCounter = 0;
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
                                $examDate = Carbon::parse($currentDate->format('Y-m-d'))->addWeek();
                                while (
                                    isset($holidayDates[$examDate->format('Y-m-d')])
                                ) {
                                    $examDate->addDay();
                                }
                            } else {
                                $examDate = Carbon::parse($currentDate->format('Y-m-d'))->addWeek();
                                while (
                                    isset($holidayDates[$examDate->format('Y-m-d')])
                                ) {
                                    $examDate->addDay();
                                }
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
            $scheduleMatrix = [];
            $examDays = [];
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
                        } elseif (isset($selfStudyDays[$currentDate->format('Y-m-d')])) {
                            $subject = $selfStudyDays[$currentDate->format('Y-m-d')];
                        } else {
                            if (isset($holidayDates[$currentDate->format('Y-m-d')])) {
                                $subject = $holidayDates[$currentDate->format('Y-m-d')];
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
                            }
                        }
                    }
                    $scheduleMatrix[$week][$day] = [
                        'date' => $currentDate->format('d/m/Y'),
                        'subject' => $subject,
                        'style' => $inlineStyle,
                        'class' => $cssClasses,
                        'MaMH' => null,
                        'is_exam' => false,
                        'is_holiday' => isset($holidayDates[$currentDate->format('Y-m-d')]),
                        'is_self_study_day' => isset($selfStudyDays[$currentDate->format('Y-m-d')]),
                    ];
                    if (isset($examDays[$currentDate->format('Y-m-d')])) {
                        $scheduleMatrix[$week][$day]['MaMH'] = $examDays[$currentDate->format('Y-m-d')]['MaMH'];
                        $scheduleMatrix[$week][$day]['is_exam'] = true;
                    } elseif ($subject && isset($subjectOccurrences[$subject])) {
                        $scheduleMatrix[$week][$day]['MaMH'] = $subject;
                    }
                }
            }
            $teacherSchedules = [];
            foreach ($giangDays as $maGV => $subjectsByGV) {
                // Chỉ lấy giáo viên có môn học xuất hiện trong scheduleMatrix
                $actualMaMHs = [];
                foreach ($scheduleMatrix as $week) {
                    foreach ($week as $day) {
                        if (!empty($day['MaMH'])) {
                            $actualMaMHs[] = $day['MaMH'];
                        }
                    }
                }
                $actualMaMHs = array_unique($actualMaMHs);
                if ($subjectsByGV->pluck('MaMH')->intersect($actualMaMHs)->isEmpty()) {
                    continue;
                }
                $teacherSchedules[$maGV] = [
                    'info' => $subjectsByGV->first()->giaovien,
                    'schedule' => []
                ];
                for ($week = 1; $week <= $totalWeeks; $week++) {
                    $teacherSchedules[$maGV]['schedule'][$week] = [];
                    foreach ($weekDays as $dayName) {
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
                        $teacherSchedules[$maGV]['schedule'][$week][$dayName] = $dayInfoFromScheduleMatrix;
                    }
                }
            }
            // --- KẾT THÚC ĐOẠN CODE LẤY $teacherSchedules ---

            return Excel::download(new \App\Exports\TeacherScheduleExport(
                $schedule,
                $teacherSchedules, // truyền đúng $teacherSchedules thay vì $giangDays
                $ngaynghis,
                $ngaytuhocs,
                $hocki,
                $chuongTrinhName,
                $phonglt,
                $phongth,
                $monhocs
            ), 'lich_gv.xlsx');
        }
        return Redirect::to('')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }
}