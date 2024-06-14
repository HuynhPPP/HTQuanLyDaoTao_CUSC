<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ScheduleExport;


use App\Models\khoadaotao;
use App\Models\chuongtrinh;
use App\Models\lophoc;
use App\Models\phonghoc;
use App\Models\tkb;
use App\Models\monhoc;
use App\Models\ngaynghi;
use App\Models\TapHuan;
use App\Models\hocki;
use App\Models\khunggio;
use App\Models\danhsachphong;
use App\Models\danhsachdangkimonhoc;


class PagesController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function about()
    {
        return view('about');
    }

    public function ministry()
    {
         $functions = [
                        [
                            'icon' => 'fa-calendar-days',
                            'text' => 'Lập thời khóa biểu',
                            'link' => route('schedules'),
                        ],
                        [
                            'icon' => 'fa-calendar-days',
                            'text' => 'Lập lịch Theo dõi phòng học',
                            'link' => route('monitorClassroom'),
                        ],
                        [
                            'icon' => 'fa-calendar-days',
                            'text' => 'Lập lịch theo dõi môn học sắp bắt đầu',
                            'link' => route('monitorSubject'),
                        ],
                        [
                            'icon' => 'fa-rectangle-list',
                            'text' => 'Lập điểm danh',
                            'link' => route('rollCall'),
                            'type' => 'link'
                        ],
                        [
                            'icon' => 'fa-calendar-days',
                            'text' => 'Lập lịch thi tháng',
                            'link' => '#',
                        ],
                        [
                            'icon' => 'fa-rectangle-list',
                            'text' => 'Lập phân công thi theo ngày',
                            'link' => '#',
                        ],
                        [
                            'icon' => 'fa-calendar-days',
                            'text' => 'Lập lịch báo cáo đồ án',
                            'link' => '#',
                        ],
                        [
                            'icon' => 'fa-rectangle-list',
                            'text' => 'Lập phân công báo cáo đồ án',
                            'link' => '#',
                        ],
                        [
                            'icon' => 'fa-scroll',
                            'text' => 'Lập bảng điểm chi tiết',
                            'link' => '#',
                        ],
                        [
                            'icon' => 'fa-scroll',
                            'text' => 'Lập bảng điểm tổng hợp',
                            'link' => '#',
                        ],
                        [
                            'icon' => 'fa-scroll',
                            'text' => 'Lập bảng báo cáo kết quả học tập',
                            'link' => '#',
                        ],
                        [
                            'icon' => 'fa-rectangle-list',
                            'text' => 'Lập danh sách xét tốt nghiệp',
                            'link' => '#',
                        ],
                        [
                            'icon' => 'fa-file',
                            'text' => 'Xuất điểm nhập điểm lên portal',
                            'link' => '#',
                        ],
                        [
                            'icon' => 'fa-rectangle-list',
                            'text' => 'Lập danh sách đề nghị ra quyết định công nhận tốt nghiệp',
                            'link' => '#',
                        ],
                        [
                            'icon' => 'fa-book',
                            'text' => 'Lập nhật ký phát bằng',
                            'link' => '#',
                        ],
                    ];
        return view('ministry', compact('functions'));
    }

    public function login()
    {
        if (session()->has('user')) {
            return redirect('/');
        }

        $captchaUrl = route('captcha');
        return view('login', compact('captchaUrl'));
    }

    public function schedules()
    {
        //if (session()->has('user')) {
            $khoadaotaos = khoadaotao::all();
            $chuongtrinhs = chuongtrinh::all();
            $lophocs = lophoc::all();
            $hockis=hocki::all();
            $tkbs = tkb::all();
            $khunggios=khunggio::all();


            return view('schedules',
            compact(
                'khoadaotaos',
                'chuongtrinhs',
                'lophocs',
                'tkbs',
                'hockis',
                'khunggios',

            ));
        // } else {
        //     return Redirect::to('error_alert')->with(['error' => 'Truy cập bị từ chối', 'redirectTo' => route('ministry')]);
        // }
    }

    public function getChuongTrinh($TenKhoaDaoTao)
    {
        $chuongtrinhs = chuongtrinh::where('TenKhoaDaoTao', $TenKhoaDaoTao)->get();
        return response()->json($chuongtrinhs);
    }

    public function getLop($MaChuongTrinh)
    {
        $lophocs = lophoc::where('MaChuongTrinh', $MaChuongTrinh)->get();
        return response()->json($lophocs);
    }

    public function getHK($MaChuongTrinh)
    {
        $hockis = hocki::where('MaChuongTrinh', $MaChuongTrinh)->get();
        return response()->json($hockis);
    }



    public function saveSchedule(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'KhoaDaoTao' => 'required|string',
            'ChuongTrinhTrienKhai' => 'required|string',
            'HocKi'=> 'required|string',
            'Lop' => 'required|string',
            'NgayHoc' => 'required|date',
        ], [
            'KhoaDaoTao.required' => 'Hãy chọn khoá đào tạo!',
            'ChuongTrinhTrienKhai.required' => 'Hãy chọn chương trình triển khai!',
            'HocKi.required' => 'Hãy chọn học kỳ!',
            'NgayHoc.required' => 'Hãy chọn ngày bắt đầu học!',
            'Lop.required' => 'Hãy chọn lớp!',
        ]);

        $hocki=hocki::where('MaHK', $request->input('HocKi'))->first();

        $schedule = new tkb();
        $schedule->TenTKB= 'THỜI KHÓA BIỂU LỚP ' . $request->input('Lop') . ' - ' . $hocki->TenHK . ' (' . $request->input('ChuongTrinhTrienKhai') . ')' ;
        $schedule->MaLop = $request->input('Lop');
        $schedule->MaHK=$request->input('HocKi');
        $schedule->NgayHoc = $request->input('NgayHoc');
        $schedule->save();

        return redirect()->route('schedule', ['TenTKB' => $schedule->TenTKB]);
    }


    public function schedule($TenTKB)
    {
        //if (session()->has('user')) {

            $schedule = tkb::where('TenTKB', $TenTKB)->first();
            $lophoc = lophoc::where('MaLop', $schedule->MaLop)->first();
            $chuongtrinh = chuongtrinh::where('MaChuongTrinh', $lophoc->MaChuongTrinh)->first();
            $phonglt = danhsachphong::where('MaLop', $lophoc->MaLop)->where('TenPhong', 'LIKE', '%Class%')->first();
            $phongth = danhsachphong::where('MaLop', $lophoc->MaLop)->where('TenPhong', 'LIKE', '%Lab%')->first();
            $hocki = hocki::where('MaHK', $schedule->MaHK)->first();
            $dsdkmn = danhsachdangkimonhoc::where('MaHK', $hocki->MaHK)->first();
            $ngaynghis = ngaynghi::all();
            $monhocs = monhoc::where('MaHK', $hocki->MaHK)->get();
            $khunggio = khunggio::all();
            return view('schedule', compact('schedule', 'chuongtrinh', 'phonglt', 'phongth', 'hocki', 'dsdkmn', 'ngaynghis', 'monhocs', 'khunggio'));
        // } else {
        //     return Redirect::to('error_alert')->with(['error' => 'Truy cập bị từ chối', 'redirectTo' => route('ministry')]);
        // }
    }


    public function deleteSchedule($TenTKB)
    {
        //if (session()->has('user')) {
            $schedules = tkb::where('TenTKB', $TenTKB);
            if ($schedules->exists()) {
                $schedules->delete();
                return redirect()->route('schedules')->with('success', 'Thời khóa biểu đã được xóa.');
            } else {
                return redirect()->route('schedules')->with('error', 'Không tìm thấy thời khóa biểu với tên đã cung cấp.');
            }
        // } else {
        //     return Redirect::to('error_alert')->with(['error' => 'Truy cập bị từ chối', 'redirectTo' => route('ministry')]);
        // }
    }

    public function exportExcel($TenTKB)
    {
        // Lấy dữ liệu từ cơ sở dữ liệu
        $schedule = tkb::where('TenTKB', $TenTKB)->first();
        $lophoc = lophoc::where('MaLop', $schedule->MaLop)->first();
        $chuongtrinh = chuongtrinh::where('MaChuongTrinh', $lophoc->MaChuongTrinh)->first();
        $phonglt = danhsachphong::where('MaLop', $lophoc->MaLop)->where('TenPhong', 'LIKE', '%Class%')->first();
        $phongth = danhsachphong::where('MaLop', $lophoc->MaLop)->where('TenPhong', 'LIKE', '%Lab%')->first();
        $hocki = hocki::where('MaHK', $schedule->MaHK)->first();
        $monhocs = monhoc::where('MaHK', $hocki->MaHK)->get();

        // Tạo và xuất file Excel
        return Excel::download(new ScheduleExport($schedule, $chuongtrinh, $phonglt, $phongth, $hocki, $monhocs), 'schedule.xlsx');
    }

    public function saveTimeSlot(Request $request, $TenTKB)
    {
        //dd($request->all());
        $request->validate([
            'khunggio'=> 'required',
        ], [
            'khunggio.required' => 'Hãy chọn khung giờ!'
        ]);
        $schedule = tkb::where('TenTKB', $TenTKB)->first();
        $hocki = hocki::where('MaHK', $schedule->MaHK)->first();

        $timeSlot = new danhsachdangkimonhoc();
        $timeSlot->TenKhungGio = $request->input('khunggio');
        $timeSlot->MaHK = $hocki->MaHK;
        $timeSlot->save();

        return redirect()->route('schedule', ['TenTKB' => $TenTKB]);

    }

    public function saveholiday(Request $request, $TenTKB)
    {
        $request->validate([
            'TenNgayNghi' => 'required|string|max:255',
            'NgayBDNghi' => 'required|date',
            'NgayKT'=> 'required|date',
        ], [
            'TenNgayNghi.required' => 'Hãy nhập tên ngày nghỉ!',
            'NgayBDNghi.required' => 'Hãy chọn ngày bắt đầu nghỉ!',
            'NgayKT.required' => 'Hãy chọn ngày kết thúc nghỉ!',
        ]);
        $absence = new ngaynghi;
        $absence->TenNgayNghi = $request->input('TenNgayNghi');
        $absence->NgayBDNghi = $request->input('NgayBDNghi');
        $absence->NgayKT = $request->input('NgayKT');
        $absence->save();

        return redirect()->route('schedule', ['TenTKB' => $TenTKB]);

    }

    public function monitorClassroom()
    {
        if (session()->has('user')) {
            $taphuans = TapHuan::all();

            return view('monitorClassroom', compact('taphuans'));
        } else {
            return Redirect::to('error_alert')->with(['error' => 'Truy cập bị từ chối', 'redirectTo' => route('ministry')]);
        }
    }

    public function monitorSubject()
    {
        if (session()->has('user')) {
            $taphuans = TapHuan::all();

            return view('monitorSubject', compact('taphuans'));
        } else {
            return Redirect::to('error_alert')->with(['error' => 'Truy cập bị từ chối', 'redirectTo' => route('ministry')]);
        }
    }

    public function rollCall()
    {
        if (session()->has('user')) {
            $taphuans = TapHuan::all();

            return view('rollCall', compact('taphuans'));
        } else {
            return Redirect::to('error_alert')->with(['error' => 'Truy cập bị từ chối', 'redirectTo' => route('ministry')]);
        }
    }
}

