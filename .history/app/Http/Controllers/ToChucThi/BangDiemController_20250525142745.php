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
            if ($request->has(['diem', 'lanThi'])) {
                foreach ($request->diem as $maSV => $diem) {
                    // Kiểm tra dữ liệu đầu vào
                    if (empty($diem) || !is_numeric($diem)) {
                        continue; // Bỏ qua nếu điểm rỗng hoặc không hợp lệ
                    }

                    $lanThi = $request->lanThi[$maSV] ?? 1;
                    $ghiChu = $request->ghiChu[$maSV] ?? null;

                    logger("Updating: MaSV=$maSV, TenMH=$tenMH, LanThi=$lanThi, Diem=$diem, GhiChu=$ghiChu");

                    // Sử dụng composite key đúng
                    DiemThi::updateOrCreate(
                        [
                            'MaSV' => $maSV,
                            'TenMH' => $tenMH,
                            'LanThi' => $lanThi  // Quan trọng: bao gồm LanThi trong điều kiện
                        ],
                        [
                            'MaLop' => $maLop,
                            'Diem' => (float) $diem,
                            'GhiChu' => $ghiChu
                        ]
                    );
                }
            }
        }

        return redirect()->back()->with('success', 'Cập nhật điểm thành công!');
    }

    public function export($maLop, $tenMH)
    {
        return Excel::download(new DiemThiExport($maLop, $tenMH), "diem-thi-{$maLop}-{$tenMH}.xlsx");
    }
}