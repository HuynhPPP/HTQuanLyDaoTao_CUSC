<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ScheduleExport;
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
    danhsachdangkimonhoc
};

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
        $data = [
            'khoadaotaos' => khoadaotao::all(),
            'chuongtrinhs' => chuongtrinh::all(),
            'lophocs' => lophoc::all(),
            'tkbs' => tkb::all(),
            'hockis' => hocki::all(),
            'khunggios' => khunggio::all(),
        ];
        return view('schedules', $data);
    }

    public function getChuongTrinh($TenKhoaDaoTao)
    {
        return response()->json(chuongtrinh::where('TenKhoaDaoTao', $TenKhoaDaoTao)->get());
    }

    public function getLop($MaChuongTrinh)
    {
        return response()->json(lophoc::where('MaChuongTrinh', $MaChuongTrinh)->get());
    }

    public function getHK($MaChuongTrinh)
    {
        return response()->json(hocki::where('MaChuongTrinh', $MaChuongTrinh)->get());
    }

    public function saveSchedule(Request $request)
    {
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
            'NgayHoc.required' => 'Hãy chọn ngày bắt đầu học!',
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

    public function schedule($TenTKB)
    {
        $schedule = tkb::where('TenTKB', $TenTKB)->first();
        $lophoc = lophoc::where('MaLop', $schedule->MaLop)->first();
        $chuongtrinh = chuongtrinh::where('MaChuongTrinh', $lophoc->MaChuongTrinh)->first();
        $phonglt = danhsachphong::where('MaLop', $lophoc->MaLop)->where('TenPhong', 'LIKE', '%Class%')->first();
        $phongth = danhsachphong::where('MaLop', $lophoc->MaLop)->where('TenPhong', 'LIKE', '%Lab%')->first();
        $hocki = hocki::where('MaHK', $schedule->MaHK)->first();
        $dsdkmn = danhsachdangkimonhoc::where('MaHK', $hocki->MaHK)->first();
        $ngaynghis = danhsachngaynghi::where('TenTKB', $TenTKB)->get()->map(function ($ngaynghi) {
            return ngaynghi::where('MaNgayNghi', $ngaynghi->MaNgayNghi)->first();
        });
        $monhocs = monhoc::where('MaHK', $hocki->MaHK)->get();
        $khunggio = khunggio::all();

        return view('schedule', compact('schedule', 'chuongtrinh', 'phonglt', 'phongth', 'hocki', 'dsdkmn', 'ngaynghis', 'monhocs', 'khunggio'));
    }

    public function deleteSchedule($TenTKB)
    {
        $schedules = tkb::where('TenTKB', $TenTKB);
        if ($schedules->exists()) {
            $schedules->delete();
            return redirect()->route('schedules')->with('success', 'Thời khóa biểu đã được xóa.');
        } else {
            return redirect()->route('schedules')->with('error', 'Không tìm thấy thời khóa biểu với tên đã cung cấp.');
        }
    }

    public function exportExcel($TenTKB)
    {
        $schedule = tkb::where('TenTKB', $TenTKB)->first();
        $lophoc = lophoc::where('MaLop', $schedule->MaLop)->first();
        $chuongtrinh = chuongtrinh::where('MaChuongTrinh', $lophoc->MaChuongTrinh)->first();
        $phonglt = danhsachphong::where('MaLop', $lophoc->MaLop)->where('TenPhong', 'LIKE', '%Class%')->first();
        $phongth = danhsachphong::where('MaLop', $lophoc->MaLop)->where('TenPhong', 'LIKE', '%Lab%')->first();
        $hocki = hocki::where('MaHK', $schedule->MaHK)->first();
        $monhocs = monhoc::where('MaHK', $hocki->MaHK)->get();

        return Excel::download(new ScheduleExport($schedule, $chuongtrinh, $phonglt, $phongth, $hocki, $monhocs), 'schedule.xlsx');
    }

    public function saveTimeSlot(Request $request, $TenTKB)
{
    // Validate request with improved error message
    $request->validate([
        'khunggio' => 'required',
    ], [
        'khunggio.required' => 'Hãy chọn khung giờ!',
    ]);

    // Retrieve schedule and semester information
    $schedule = tkb::where('TenTKB', $TenTKB)->first();
    $hocki = hocki::where('MaHK', $schedule->MaHK)->first();

    // Find existing time slot
    $timeSlot = danhsachdangkimonhoc::where('MaHK', $hocki->MaHK)->update(
        ['TenKhungGio' => $request->input('khunggio')]);
    //DD(request()->all());
    if (!$timeSlot) {
        // Update existing time slot (delete and recreate)
        $timeSlot = new danhsachdangkimonhoc();
        $timeSlot->TenKhungGio = $request->input('khunggio');
        $timeSlot->MaHK = $hocki->MaHK;
        $timeSlot->save();
    }


    // Redirect with success message
    return redirect()->route('schedule', ['TenTKB' => $TenTKB]);
}

    public function saveholiday(Request $request, $TenTKB)
    {
        $request->validate([
            'TenNgayNghi' => 'required|string|max:255',
            'NgayBDNghi' => 'required|date',
            'NgayKT' => 'required|date',
        ], [
            'TenNgayNghi.required' => 'Hãy nhập tên ngày nghỉ!',
            'NgayBDNghi.required' => 'Hãy chọn ngày bắt đầu nghỉ!',
            'NgayKT.required' => 'Hãy chọn ngày kết thúc nghỉ!',
        ]);

        ngaynghi::create($request->only('TenNgayNghi', 'NgayBDNghi', 'NgayKT'));

        return redirect()->route('schedule', ['TenTKB' => $TenTKB]);
    }

    public function monitorClassroom()
    {
        return session()->has('user')
            ? view('monitorClassroom', ['taphuans' => TapHuan::all()])
            : Redirect::to('error_alert')->with(['error' => 'Truy cập bị từ chối', 'redirectTo' => route('ministry')]);
    }

    public function monitorSubject()
    {
        return session()->has('user')
            ? view('monitorSubject', ['taphuans' => TapHuan::all()])
            : Redirect::to('error_alert')->with(['error' => 'Truy cập bị từ chối', 'redirectTo' => route('ministry')]);
    }

    public function rollCall()
    {
        return session()->has('user')
            ? view('rollCall', ['taphuans' => TapHuan::all()])
            : Redirect::to('error_alert')->with(['error' => 'Truy cập bị từ chối', 'redirectTo' => route('ministry')]);
    }
}
