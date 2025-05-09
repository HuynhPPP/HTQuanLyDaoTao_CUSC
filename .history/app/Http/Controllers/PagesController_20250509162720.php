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

class PagesController extends Controller
{
    public function index()
    {
        $functions = [
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập thời khóa biểu',
                'link' => route('schedules'),
                'color' => 'bg-primary',
                'value' => 10
            ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập lịch Theo dõi phòng học',
                'link' => route('monitorClassroom'),
                'color' => 'bg-danger',
                'value' => 5
            ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập lịch theo dõi môn học sắp bắt đầu',
                'link' => route('monitorSubject'),
                'color' => 'bg-warning',
                'value' => 8
            ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập điểm danh',
                'link' => route('rollCall'),
                'color' => 'bg-success',
                'value' => 12
            ],
            [
                'icon' => 'far fa-newspaper',
                'text' => 'Lập lịch thi tháng',
                'link' => '#',
                'color' => 'bg-info',
                'value' => null
            ],
            [
                'icon' => 'fa-rectangle-list',
                'text' => 'Lập phân công thi theo ngày',
                'link' => '#',
                'color' => 'bg-secondary',
                'value' => null
            ],
            [
                'icon' => 'fa-calendar-days',
                'text' => 'Lập lịch báo cáo đồ án',
                'link' => '#',
                'color' => 'bg-primary',
                'value' => null
            ],
            [
                'icon' => 'fa-rectangle-list',
                'text' => 'Lập phân công báo cáo đồ án',
                'link' => '#',
                'color' => 'bg-danger',
                'value' => null
            ],
            [
                'icon' => 'fa-scroll',
                'text' => 'Lập bảng điểm chi tiết',
                'link' => '#',
                'color' => 'bg-warning',
                'value' => null
            ],
            [
                'icon' => 'fa-scroll',
                'text' => 'Lập bảng điểm tổng hợp',
                'link' => '#',
                'color' => 'bg-success',
                'value' => null
            ],
            [
                'icon' => 'fa-scroll',
                'text' => 'Lập bảng báo cáo kết quả học tập',
                'link' => '#',
                'color' => 'bg-info',
                'value' => null
            ],
            [
                'icon' => 'fa-rectangle-list',
                'text' => 'Lập danh sách xét tốt nghiệp',
                'link' => '#',
                'color' => 'bg-secondary',
                'value' => null
            ],
            [
                'icon' => 'fa-file',
                'text' => 'Xuất điểm nhập điểm lên portal',
                'link' => '#',
                'color' => 'bg-primary',
                'value' => null
            ],
            [
                'icon' => 'fa-rectangle-list',
                'text' => 'Lập danh sách đề nghị ra quyết định công nhận tốt nghiệp',
                'link' => '#',
                'color' => 'bg-danger',
                'value' => null
            ],
            [
                'icon' => 'fa-book',
                'text' => 'Lập nhật ký phát bằng',
                'link' => '#',
                'color' => 'bg-warning',
                'value' => null
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
            ['icon' => 'fa-calendar-days', 'text' => 'Lập lịch thi tháng', 'link' => '#'],
            ['icon' => 'fa-rectangle-list', 'text' => 'Lập phân công thi theo ngày', 'link' => '#'],
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
        return Redirect::to('error_alert')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }

    public function getChuongTrinh($TenKhoaDaoTao)
    {
        if (session()->has('user')) {
            return response()->json(chuongtrinh::where('TenKhoaDaoTao', $TenKhoaDaoTao)->get());
        }
        return Redirect::to('error_alert')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }

    public function getLop($MaChuongTrinh)
    {
        if (session()->has('user')) {
            return response()->json(lophoc::where('MaChuongTrinh', $MaChuongTrinh)->get());
        }
        return Redirect::to('error_alert')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }

    public function getHK($MaChuongTrinh)
    {
        if (session()->has('user')) {
            return response()->json(hocki::where('MaChuongTrinh', $MaChuongTrinh)->get());
        }
        return Redirect::to('error_alert')->with([
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
            $schedule = new tkb([
                'TenTKB' => 'THỜI KHÓA BIỂU LỚP ' . $request->input('Lop') . ' - ' . $hocki->TenHK . ' (' . $request->input('ChuongTrinhTrienKhai') . ')',
                'MaLop' => $request->input('Lop'),
                'MaHK' => $request->input('HocKi'),
                'NgayHoc' => $request->input('NgayHoc'),
            ]);
            $schedule->save();

            return redirect()->route('schedule', ['TenTKB' => $schedule->TenTKB]);
        }
        return Redirect::to('error_alert')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }

    public function schedule($TenTKB)
    {
        if (session()->has('user')) {
            $schedule = tkb::find($TenTKB);
            $lophoc = lophoc::find($schedule->MaLop);
            $chuongtrinh = chuongtrinh::find($lophoc->MaChuongTrinh);
            $phonglt = danhsachphong::find($lophoc->MaLop)->where('TenPhong', 'LIKE', '%Class%')->first();
            $phongth = danhsachphong::find($lophoc->MaLop)->where('TenPhong', 'LIKE', '%Lab%')->first();
            $hocki = hocki::find($schedule->MaHK);
            $dsmh = danhsachmonhoc::find($hocki->MaHK);
            $ngaynghis = danhsachngaynghi::where('TenTKB', $TenTKB)->get()->pluck('ngayNghi');
            $monhocs = danhsachmonhoc::where('MaHK', $hocki->MaHK)->get()->pluck('monhoc');
            $khunggio = khunggio::all();
            $ngaytuhocs = ngaytuhoc::where('TenTKB', $schedule->TenTKB)->get();
            return view('schedule', compact('schedule', 'chuongtrinh', 'phonglt', 'phongth', 'hocki', 'dsmh', 'ngaynghis', 'monhocs', 'khunggio', 'ngaytuhocs'));
        }
        return Redirect::to('error_alert')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }

    public function deleteSchedule($TenTKB)
    {
        if (session()->has('user')) {
            $schedule = tkb::where('TenTKB', $TenTKB);
            if ($schedule->exists()) {
                $schedule->delete();
                return redirect()->route('schedules')->with('success', 'Thời khóa biểu đã được xóa.');
            } else {
                return redirect()->route('schedules')->with('error', 'Không tìm thấy thời khóa biểu với tên đã cung cấp.');
            }
        }
        return Redirect::to('error_alert')->with([
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
        return Redirect::to('error_alert')->with([
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

            tkb::where('TenTKB', $TenTKB)->update([
                'NgayHoc' => $request->input('NgayHoc'),
            ]);

            return redirect()->route('schedule', ['TenTKB' => $TenTKB]);
        }
        return Redirect::to('error_alert')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }

    public function saveTimeSlot(Request $request, $TenTKB)
    {
        if (session()->has('user')) {
            $request->validate([
                'khunggio' => 'required',
            ], [
                'khunggio.required' => 'Hãy chọn khung giờ!',
            ]);

            $schedule = tkb::where('TenTKB', $TenTKB)->first();
            $hocki = hocki::where('MaHK', $schedule->MaHK)->first();

            danhsachmonhoc::updateOrCreate(
                ['MaHK' => $hocki->MaHK],
                ['TenKhungGio' => $request->input('khunggio')]
            );

            return redirect()->route('schedule', ['TenTKB' => $TenTKB]);
        }
        return Redirect::to('error_alert')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }

    public function saveholiday(Request $request, $TenTKB)
    {
        if (session()->has('user')) {
            try {
                $request->validate([
                    'TenNgayNghi' => 'required|string|max:255',
                    'NgayBDNghi' => 'required|date',
                    'NgayKT' => 'required|date|after_or_equal:NgayBDNghi',
                ], [
                    'TenNgayNghi.required' => 'Hãy nhập tên ngày nghỉ!',
                    'NgayBDNghi.required' => 'Hãy chọn ngày bắt đầu nghỉ!',
                    'NgayKT.after_or_equal' => 'Ngày kết thúc phải sau ngày bắt đầu!',
                ]);

                $ngaynghimoi = ngaynghi::create($request->only('TenNgayNghi', 'NgayBDNghi', 'NgayKT'));

                danhsachngaynghi::create([
                    'MaNgayNghi' => $ngaynghimoi->MaNgayNghi,
                    'TenTKB' => $TenTKB,
                ]);

                return redirect()->route('schedule', ['TenTKB' => $TenTKB]);
            } catch (Exception $e) {
                return redirect()->route('error_alert')->with([
                    'error' => 'Ngày kết thúc phải sau ngày bắt đầu!',
                    'redirectTo' => route('schedule', ['TenTKB' => $TenTKB]),
                ]);
            }
        }
        return Redirect::to('error_alert')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }

    public function saveSelfStudy(Request $request, $TenTKB)
    {
        if (session()->has('user')) {
            try {
                $request->validate([
                    'ngaytuhoc' => 'required',
                    'NgayBDTuHoc' => 'required|date',
                    'NgayKTTuHoc' => 'required|date|after_or_equal:NgayBDTuHoc',
                ], [
                    'ngaytuhoc.required' => 'Hãy nhập tên ngày tự học!',
                    'NgayBDTuHoc.required' => 'Hãy chọn ngày bắt đầu tự học!',
                    'NgayKTTuHoc.after_or_equal' => 'Ngày kết thúc phải sau ngày bắt đầu!',
                ]);

                ngaytuhoc::create([
                    'TenTKB' => $TenTKB,
                    'TenNgayTuHoc' => $request->input('ngaytuhoc'),
                    'NgayBDTuHoc' => $request->input('NgayBDTuHoc'),
                    'NgayKTTuHoc' => $request->input('NgayKTTuHoc'),
                ]);

                return redirect()->route('schedule', ['TenTKB' => $TenTKB]);
            } catch (Exception $e) {
                return redirect()->route('error_alert')->with([
                    'error' => 'Ngày kết thúc phải sau ngày bắt đầu!',
                    'redirectTo' => route('schedule', ['TenTKB' => $TenTKB]),
                ]);
            }
        }
        return Redirect::to('error_alert')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }

    public function monitorClassroom()
    {
        if (session()->has('user')) {
            return view('monitorClassroom', ['taphuans' => TapHuan::all()]);
        }
        return Redirect::to('error_alert')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }

    public function monitorSubject()
    {
        if (session()->has('user')) {
            return view('monitorSubject', ['taphuans' => TapHuan::all()]);
        }
        return Redirect::to('error_alert')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }

    public function rollCall()
    {
        if (session()->has('user')) {
            return view('rollCall', ['taphuans' => TapHuan::all()]);
        }
        return Redirect::to('error_alert')->with([
            'error' => 'Truy cập bị từ chối',
            'redirectTo' => route('ministry'),
        ]);
    }
}