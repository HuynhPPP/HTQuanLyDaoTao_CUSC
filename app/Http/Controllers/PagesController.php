<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Redirect;
use App\Models\TapHuan;
use App\Models\bangcapcanbo;
use App\Models\chucvu;
use App\Models\hocvi;
use App\Models\phutrach;
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
            $taphuans = TapHuan::all();

            return view('schedules', compact('taphuans'));
        } else {
            return Redirect::to('error_alert')->with(['error' => 'Truy cập bị từ chối', 'redirectTo' => route('ministry')]);
        }
    }

    public function submitSchedule(Request $request)
    {
        // Validate the request data
        $request->validate([
            'TenTKB' => 'required|string|max:255',
            // Các trường khác tương tự
        ]);

        // Lưu dữ liệu hoặc xử lý logic cần thiết
        $TenTKB = $request->input('TenTKB');

        // Trả về view với dữ liệu đã nhập
        return view('schedules', compact('TenTKB'));
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
