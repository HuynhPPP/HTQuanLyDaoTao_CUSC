<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ScheduleExport;
use Exception;
use App\Models\{
    khoadaotao,
    chuongtrinh,
    lophoc,
    phonghoc,
    tkb,
    monhoc,
    ngaynghi,
    danhsachngaynghi,
    TapHuan,
    hocki,
    khunggio,
    danhsachphong,
    danhsachmonhoc,
    ngaytuhoc
};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
                'link' => route('monitorClassroom'),
                'color' => 'bg-info',
            ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập lịch theo dõi môn học sắp bắt đầu',
                'link' => route('monitorSubject'),
                'color' => 'bg-info',
            ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập điểm danh',
                'link' => route('rollCall'),
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
                'text' => 'Lập phân công báo cáo đồ án',
                'link' => '#',
                'color' => 'bg-info',
            ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập bảng điểm chi tiết',
                'link' => '#',
                'color' => 'bg-info',
            ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập bảng điểm tổng hợp',
                'link' => '#',
                'color' => 'bg-info',
            ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập bảng báo cáo kết quả học tập',
                'link' => '#',
                'color' => 'bg-info',
            ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập danh sách xét tốt nghiệp',
                'link' => '#',
                'color' => 'bg-info',
            ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Xuất điểm nhập điểm',
                'link' => route('bangdiem.chon'),
                'color' => 'bg-info',
            ],
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
        return view('index', compact('functions'));
    }
    public function about()
    {
        return view('about');
    }

    public function ministry()
    {
        $functions = [
            ['icon' => 'fa-calendar-days', 'text' => 'Lập thời khóa biểu', 'link' => route('schedules')],
            ['icon' => 'fa-calendar-days', 'text' => 'Lập lịch Theo dõi phòng học', 'link' => route('monitorClassroom')],
            ['icon' => 'fa-calendar-days', 'text' => 'Lập lịch theo dõi môn học sắp bắt đầu', 'link' => route('monitorSubject')],
            ['icon' => 'fa-rectangle-list', 'text' => 'Lập điểm danh', 'link' => route('rollCall'), 'type' => 'link'],
            ['icon' => 'fa-calendar-days', 'text' => 'Lập lịch thi', 'link' => route('lichthi.index'), 'type' => 'link'],
            ['icon' => 'fa-rectangle-list', 'text' => 'Lập phân công thi', 'link' => '#'],
            ['icon' => 'fa-calendar-days', 'text' => 'Lập lịch báo cáo đồ án', 'link' => '#'],
            ['icon' => 'fa-rectangle-list', 'text' => 'Lập phân công báo cáo đồ án', 'link' => '#'],
            ['icon' => 'fa-scroll', 'text' => 'Lập bảng điểm chi tiết', 'link' => '#'],
            ['icon' => 'fa-scroll', 'text' => 'Lập bảng điểm tổng hợp', 'link' => '#'],
            ['icon' => 'fa-scroll', 'text' => 'Lập bảng báo cáo kết quả học tập', 'link' => '#'],
            ['icon' => 'fa-rectangle-list', 'text' => 'Lập danh sách xét tốt nghiệp', 'link' => '#'],
            ['icon' => 'fa-file', 'text' => 'Xuất điểm nhập điểm lên portal', 'link' => '#'],
            ['icon' => 'fa-rectangle-list', 'text' => 'Lập danh sách đề nghị ra quyết định công nhận tốt nghiệp', 'link' => '#'],
            ['icon' => 'fa-book', 'text' => 'Lập nhật ký phát bằng', 'link' => '#'],
        ];
        return view('ministry', compact('functions'));
    }

    public function login()
    {
        if (session()->has('user')) {
            return redirect('/');
        }

        return view('login', ['captchaUrl' => route('captcha')]);
    }

    public function schedules()
    {
        if (session()->has('user')) {
            $data = [
                'khoadaotaos' => khoadaotao::all(),
                'tkbs' => tkb::all(),
            ];
            return view('schedules', $data);
        }
        return Redirect::to('')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
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

    public function saveSchedule(Request $request)
    {
        if (session()->has('user')) {
            $request->validate([
                'KhoaDaoTao' => 'required|string',
                'ChuongTrinhTrienKhai' => 'required|string',
                'HocKi' => 'required|string',
                'Lop' => 'required|string',
                'NgayHoc' => 'required|date',
            ], [
                'KhoaDaoTao.required' => 'Hãy chọn khoá đào tạo!',
                'ChuongTrinhTrienKhai.required' => 'Hãy chọn chương trình triển khai!',
                'HocKi.required' => 'Hãy chọn học kỳ!',
                'NgayHoc' => 'Ngày bắt đầu học không được là thứ 7 hoặc chủ nhật!',
                'Lop.required' => 'Hãy chọn lớp!',
            ]);

            $hocki = hocki::where('MaHK', $request->input('HocKi'))->first();
            $scheduleName = 'THỜI KHÓA BIỂU LỚP ' . $request->input('Lop') . ' - ' . $hocki->TenHK . ' (' . $request->input('ChuongTrinhTrienKhai') . ')';

            // Kiểm tra xem thời khóa biểu với tên này đã tồn tại chưa
            $existingSchedule = tkb::where('TenTKB', $scheduleName)->first();

            if ($existingSchedule) {
                // Nếu đã tồn tại, quay lại form với thông báo lỗi
                return redirect()->back()->withInput()->with('error', 'Thời khóa biểu với thông tin lớp, học kỳ và chương trình này đã tồn tại!');
            }

            // Nếu chưa tồn tại, tạo và lưu bản ghi mới
            $schedule = new tkb([
                'TenTKB' => $scheduleName,
                'MaLop' => $request->input('Lop'),
                'MaHK' => $request->input('HocKi'),
                'NgayHoc' => $request->input('NgayHoc'),
            ]);
            $schedule->save();

            // Sau khi lưu thời khóa biểu thành công
            // Tìm phòng thực hành được gán cho lớp này trong danhsachphong
            // $phongThucHanhDaGan = danhsachphong::where('MaLop', $schedule->MaLop)->first();

            // if ($phongThucHanhDaGan) {
            //     // Lấy tên phòng thực hành
            //     $tenPhongThucHanh = $phongThucHanhDaGan->TenPhong;

            //     // Cập nhật trạng thái của phòng thực hành trong bảng phonghoc
            //     $phongHoc = phonghoc::where('TenPhong', $tenPhongThucHanh)->first();

            //     if ($phongHoc) {
            //         $phongHoc->TrangThai = 'Đang sử dụng';
            //         $phongHoc->save();
            //     }
            // }

            return redirect()->route('schedule', ['TenTKB' => $schedule->TenTKB]);
        }
        return Redirect::to('')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }

    public function schedule($TenTKB)
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
            $monhocs = danhsachmonhoc::where('MaHK', $hocki->MaHK)->get()->pluck('monhoc');
            $khunggio = khunggio::all();
            $ngaytuhocs = ngaytuhoc::where('TenTKB', $schedule->TenTKB)->get();

            return view('schedule', compact('schedule', 'chuongtrinh', 'phonglt', 'phongth', 'hocki', 'dsmh', 'ngaynghis', 'monhocs', 'khunggio', 'ngaytuhocs', 'khoaDaoTaoName', 'chuongTrinhName'));
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
            $dsmh = danhsachmonhoc::find($hocki->MaHK);
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
                $schedule->save();

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
                danhsachmonhoc::updateOrCreate(
                    ['MaHK' => $hocki->MaHK],
                    ['TenKhungGio' => $tenKhungGio]
                );

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
            \App\Models\danhsachmonhoc::where('MaHK', $maHK)->delete();

            // Lưu môn học mới
            if (!empty($selectedSubjects)) {
                foreach ($selectedSubjects as $subject) {
                    $created = \App\Models\danhsachmonhoc::create([
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
}