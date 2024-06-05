<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Redirect;
use App\Models\khoadaotao;
use App\Models\chuongtrinh;
use App\Models\lophoc;
use App\Models\phonghoc;
use App\Models\tkb;
use App\Models\theodoimhsapbatdau;
use App\Models\TapHuan;



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
        if (session()->has('user')) {
            $khoadaotaos = khoadaotao::all();
            $chuongtrinhs = chuongtrinh::all();
            $lophocs = lophoc::all();
            $phongLTs = phonghoc::where('LoaiPhong', 'LyThuyet')->get();
            $phongTHs = phonghoc::where('LoaiPhong', 'ThucHanh')->get();
            $tkbs = tkb::all();


            return view('schedules', 
            compact(
                'khoadaotaos',
                'chuongtrinhs',
                'lophocs',
                'phongLTs',
                'phongTHs',
                'tkbs'
                
            ));
        } else {
            return Redirect::to('error_alert')->with(['error' => 'Truy cập bị từ chối', 'redirectTo' => route('ministry')]);
        }
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



    public function saveSchedule(Request $request)
    {
        $request->validate([
            'TenTKB' => 'required|string|max:255',
            'KhoaDaoTao' => 'required|string',
            'ChuongTrinhTrienKhai' => 'required|string',
            'Lop' => 'required|string',
            'TuanHoc' => 'required|integer|max:24',
        ], [
            'TenTKB.required' => 'Hãy nhập tên thời khóa biểu!',
            'TenTKB.max' => 'Tên thời khóa biểu không được vượt quá 255 ký tự.',
            'KhoaDaoTao.required' => 'Hãy chọn khoá đào tạo!',
            'ChuongTrinhTrienKhai.required' => 'Hãy chọn chương trình triển khai!',
            'Lop.required' => 'Hãy chọn lớp!',
            'TuanHoc.required' => 'Hãy chọn tuần học!',
            'TuanHoc.integer' => 'Tuần học phải là một số nguyên.',
            'TuanHoc.max' => 'Tuần học không được vượt quá 24 tuần.',
        ]);

        $schedule = new tkb();
        $schedule->TenTKB = $request->input('TenTKB');
        $schedule->MaLop = $request->input('Lop');
        $schedule->TuanHoc = $request->input('TuanHoc');
        $schedule->save();

        return redirect()->route('schedule', ['TenTKB' => $schedule->TenTKB]);
    }


    public function schedule($TenTKB)
    {
        if (session()->has('user')) {
            
            $schedule = tkb::where('TenTKB', $TenTKB)->first();  
            $lophoc = lophoc::where('MaLop', $schedule->MaLop)->first();
            $chuongtrinh = chuongtrinh::where('MaChuongTrinh', $lophoc->MaChuongTrinh)->first();
            $theodoimh = theodoimhsapbatdau::where('MaTheoDoiMH', $schedule->MaTheoDoiMH)->first();
            return view('schedule', compact('schedule', 'chuongtrinh', 'theodoimh'));
        } else {
            return Redirect::to('error_alert')->with(['error' => 'Truy cập bị từ chối', 'redirectTo' => route('ministry')]);
        }
    }


    public function deleteSchedule($TenTKB)
    {
        if (session()->has('user')) {
            $schedules = tkb::where('TenTKB', $TenTKB);
            if ($schedules->exists()) {
                $schedules->delete();
                return redirect()->route('schedules')->with('success', 'Thời khóa biểu đã được xóa.');
            } else {
                return redirect()->route('schedules')->with('error', 'Không tìm thấy thời khóa biểu với tên đã cung cấp.');
            }
        } else {
            return Redirect::to('error_alert')->with(['error' => 'Truy cập bị từ chối', 'redirectTo' => route('ministry')]);
        }
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
