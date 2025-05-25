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

        return view('tochucthi.diemthi.add_point', compact('dsLop', 'dsMon'));
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
        $danhSachSV = danhsachsv::where("MaLop ", $maLop)->get();
        return view('tochucthi.diemthi.add_point', compact('maLop', 'tenMH', 'danhSachSV'));
    }

    public function import(Request $request)
    {
        $maLop = $request->maLop;
        $tenMH = $request->tenMH;

        if ($request->hasFile('file')) {
            Excel::import(new DiemThiImport($maLop, $tenMH), $request->file('file'));
        } else {
            // Xử lý nhập điểm thủ công
            foreach ($request->diem as $maSV => $diem) {
                DiemThi::updateOrCreate(
                    [
                        'MaSV' => $maSV,
                        'TenMH' => $tenMH,
                        'MaLop' => $maLop,
                        'LanThi' => $request->lanThi[$maSV]
                    ],
                    [
                        'Diem' => $diem,
                        'GhiChu' => $request->ghiChu[$maSV] ?? null
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Nhập điểm thành công!');
    }

    public function export($maLop, $tenMH)
    {
        return Excel::download(new DiemThiExport($maLop, $tenMH), "diem-thi-{$maLop}-{$tenMH}.xlsx");
    }
}