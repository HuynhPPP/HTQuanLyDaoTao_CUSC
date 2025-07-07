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
}