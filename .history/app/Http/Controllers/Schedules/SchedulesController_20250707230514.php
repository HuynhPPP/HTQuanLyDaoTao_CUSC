<?php

namespace App\Http\Controllers\Schedules;

use App\Http\Controllers\Controller;
use App\Models\khoadaotao;
use App\Models\tkb;
use App\Models\phonghoc;
use App\Models\danhsachphong;
use App\Models\hocki;
use Illuminate\Support\Facades\Redirect;
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
}
