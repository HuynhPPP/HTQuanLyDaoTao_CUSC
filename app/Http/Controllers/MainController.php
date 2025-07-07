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

class MainController extends Controller
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
    
}