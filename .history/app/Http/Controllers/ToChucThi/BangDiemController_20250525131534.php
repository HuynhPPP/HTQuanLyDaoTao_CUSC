<?php
namespace App\Http\Controllers\ToChucThi;

use App\Http\Controllers\Controller;
use App\Models\DiemThi;
use App\Models\danhsachsv;
use App\Imports\DiemThiImport;
use App\Exports\DiemThiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class BangDiemController extends Controller
{
    public function show($maLop, $tenMH)
    {
        $danhSachSV = // Query lấy danh sách sinh viên của lớp
        return view('tochucthi.diemthi.nhap-diem', compact('maLop', 'tenMH', 'danhSachSV'));
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