<?php
namespace App\Http\Controllers\ToChucThi;

use App\Http\Controllers\Controller;
use App\Models\danhsachsv;
use App\Models\DiemThi;
use App\Models\LopHoc;
use App\Models\MonHoc;
use App\Imports\DiemThiImport;
use App\Exports\DiemThiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class BangDiemController extends Controller
{
    public function chonLopVaMon()
    {
        // Lấy danh sách lớp và môn học từ DB
        $dsLop = LopHoc::all();
        $dsMon = MonHoc::all();

        return view('tochucthi.diemthi.choose_schedules', compact('dsLop', 'dsMon'));
    }

    public function xemBangDiem(Request $request)
    {
        $maLop = $request->input('maLop');
        $tenMH = $request->input('tenMH');

        // Chuyển đến route có tên `bangdiem.show`
        return redirect()->route('bangdiem.show', ['maLop' => $maLop, 'tenMH' => $tenMH]);
    }


    public function show($maLop, $tenMH)
    {
        $lop = LopHoc::where('MaLop', $maLop)->first();
        $danhSachSV = DanhSachSV::with([
            'sinhVien',
            'diem' => function ($query) use ($tenMH) {
                $query->where('TenMH', $tenMH);
            }
        ])->where('MaLop', $maLop)->get();
        return view('tochucthi.diemthi.add_point', compact('maLop', 'tenMH', 'danhSachSV', 'lop'));
    }


    public function import(Request $request)
    {
        $maLop = $request->maLop;
        $tenMH = $request->tenMH;

        if ($request->hasFile('file')) {
            Excel::import(new DiemThiImport($maLop, $tenMH), $request->file('file'));
        } else {
            // Xử lý nhập điểm thủ công
            if ($request->has('diem') && is_array($request->diem)) {
                $successCount = 0;
                $errorMessages = [];

                foreach ($request->diem as $maSV => $diem) {
                    // Bỏ qua nếu điểm rỗng
                    if (empty($diem) && $diem !== '0') {
                        continue;
                    }

                    // Validate điểm
                    if (!is_numeric($diem) || $diem < 0 || $diem > 100) {
                        $errorMessages[] = "Điểm của sinh viên $maSV không hợp lệ (phải từ 0-100)";
                        continue;
                    }

                    $lanThi = $request->lanThi[$maSV] ?? 1;
                    $ghiChu = $request->ghiChu[$maSV] ?? null;

                    try {
                        // Tìm record hiện tại dựa trên composite key
                        $existingRecord = DiemThi::where([
                            ['MaSV', '=', $maSV],
                            ['TenMH', '=', $tenMH],
                            ['LanThi', '=', $lanThi]
                        ])->first();

                        if ($existingRecord) {
                            // Cập nhật record hiện có
                            $existingRecord->update([
                                'MaLop' => $maLop,
                                'Diem' => (float) $diem,
                                'GhiChu' => $ghiChu
                            ]);
                        } else {
                            // Tạo record mới
                            DiemThi::create([
                                'MaSV' => $maSV,
                                'TenMH' => $tenMH,
                                'MaLop' => $maLop,
                                'LanThi' => $lanThi,
                                'Diem' => (float) $diem,
                                'GhiChu' => $ghiChu
                            ]);
                        }
                        $successCount++;
                    } catch (\Exception $e) {
                        $errorMessages[] = "Lỗi khi cập nhật điểm cho sinh viên $maSV: " . $e->getMessage();
                    }
                }

                // Trả về thông báo phù hợp
                if ($successCount > 0) {
                    $message = "Đã cập nhật điểm thành công.";
                    if (!empty($errorMessages)) {
                        $message .= "\n" . implode("\n", $errorMessages);
                        return redirect()->back()->with('warning', $message);
                    }
                    return redirect()->back()->with('success', $message);
                } else if (!empty($errorMessages)) {
                    return redirect()->back()->with('error', implode("\n", $errorMessages));
                }
            }
        }

        return redirect()->back()->with('info', 'Không có thay đổi nào được thực hiện.');
    }

    public function export($maLop, $tenMH)
    {
        return Excel::download(new DiemThiExport($maLop, $tenMH), "diem-thi-{$maLop}-{$tenMH}.xlsx");
    }
}