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
            if ($request->has('diem') || $request->has('lanThi')) {
                $successCount = 0;
                $updateCount = 0;
                $errorMessages = [];
                $processedStudents = []; // Theo dõi sinh viên đã xử lý

                // Lấy danh sách tất cả sinh viên cần xử lý
                $allStudents = array_unique(array_merge(
                    array_keys($request->diem ?? []),
                    array_keys($request->lanThi ?? [])
                ));

                foreach ($allStudents as $maSV) {
                    $diem = $request->diem[$maSV] ?? null;
                    $lanThi = $request->lanThi[$maSV] ?? 1;
                    $ghiChu = $request->ghiChu[$maSV] ?? null;

                    // Bỏ qua nếu cả điểm và lần thi đều rỗng/không thay đổi
                    if ((empty($diem) && $diem !== '0') && empty($lanThi)) {
                        continue;
                    }

                    // Validate điểm nếu có
                    if (!empty($diem) || $diem === '0') {
                        if (!is_numeric($diem) || $diem < 0 || $diem > 100) {
                            $errorMessages[] = "Điểm của sinh viên $maSV không hợp lệ (phải từ 0-100)";
                            continue;
                        }
                    }

                    try {
                        // Tìm tất cả records hiện tại của sinh viên cho môn học này
                        $existingRecords = DiemThi::where([
                            ['MaSV', '=', $maSV],
                            ['TenMH', '=', $tenMH]
                        ])->get();

                        $recordUpdated = false;

                        if ($existingRecords->isNotEmpty()) {
                            // Tìm record có lần thi mới để cập nhật
                            $targetRecord = $existingRecords->where('LanThi', $lanThi)->first();

                            if ($targetRecord) {
                                // Cập nhật record đã tồn tại với lần thi tương ứng
                                $updateData = ['MaLop' => $maLop];

                                if (!empty($diem) || $diem === '0') {
                                    $updateData['Diem'] = (float) $diem;
                                }
                                if ($ghiChu !== null) {
                                    $updateData['GhiChu'] = $ghiChu;
                                }

                                $targetRecord->update($updateData);
                                $recordUpdated = true;
                            } else {
                                // Không tìm thấy record với lần thi mới
                                // Kiểm tra xem có phải đang thay đổi lần thi không
                                $oldRecord = $existingRecords->first(); // Lấy record đầu tiên

                                if ($oldRecord->LanThi != $lanThi) {
                                    // Đang thay đổi lần thi - xóa record cũ và tạo mới
                                    $oldRecord->delete();

                                    DiemThi::create([
                                        'MaSV' => $maSV,
                                        'TenMH' => $tenMH,
                                        'MaLop' => $maLop,
                                        'LanThi' => $lanThi,
                                        'Diem' => !empty($diem) || $diem === '0' ? (float) $diem : $oldRecord->Diem,
                                        'GhiChu' => $ghiChu !== null ? $ghiChu : $oldRecord->GhiChu
                                    ]);
                                    $updateCount++;
                                    $recordUpdated = true;
                                }
                            }
                        }

                        if (!$recordUpdated) {
                            // Tạo record mới nếu chưa tồn tại
                            DiemThi::create([
                                'MaSV' => $maSV,
                                'TenMH' => $tenMH,
                                'MaLop' => $maLop,
                                'LanThi' => $lanThi,
                                'Diem' => !empty($diem) || $diem === '0' ? (float) $diem : null,
                                'GhiChu' => $ghiChu
                            ]);
                        }

                        if (!empty($diem) || $diem === '0') {
                            $successCount++;
                        }

                    } catch (\Exception $e) {
                        $errorMessages[] = "Lỗi khi cập nhật cho sinh viên $maSV: " . $e->getMessage();
                    }
                }

                // Tạo thông báo chi tiết
                $messages = [];
                if ($successCount > 0) {
                    $messages[] = "Đã cập nhật điểm";
                }
                if ($updateCount > 0) {
                    $messages[] = "Đã cập nhật lần thi";
                }

                // Trả về thông báo phù hợp
                if (!empty($messages)) {
                    $message = implode(" và ", $messages) . " thành công.";
                    if (!empty($errorMessages)) {
                        $message .= "\nCó một số lỗi:\n" . implode("\n", $errorMessages);
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

    public function nhapDiemChiTiet(Request $request)
    {
        $validatedData = $request->validate([
            'MaSV' => 'required|exists:sinhvien,MaSV',
            'TenMH' => 'required|exists:monhoc,TenMH',
            'MaLop' => 'required|exists:lophoc,MaLop',
            'LanThi' => 'required|integer|min:1',
            'DiemLyThuyet' => 'required|numeric|min:0|max:100',
            'DiemThucHanh' => 'required|numeric|min:0|max:100',
            'DiemDuAn' => 'required|numeric|min:0|max:100',
            'GhiChu' => 'nullable|string'
        ]);

        try {
            $diemThi = DiemThi::createOrUpdateDiem(
                $validatedData['MaSV'],
                $validatedData['TenMH'],
                $validatedData['MaLop'],
                $validatedData['LanThi'],
                $validatedData['DiemLyThuyet'],
                $validatedData['DiemThucHanh'],
                $validatedData['DiemDuAn'],
                $validatedData['GhiChu'] ?? null
            );

            return response()->json([
                'message' => 'Nhập điểm thành công',
                'diemThi' => $diemThi->getChiTietDiem()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi nhập điểm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function xuatBaoCaoDiem($maLop, $tenMH)
    {
        $danhSachDiem = DiemThi::where('MaLop', $maLop)
            ->where('TenMH', $tenMH)
            ->with('sinhVien')
            ->get()
            ->map(function($diemThi) {
                $chiTietDiem = $diemThi->getChiTietDiem();
                return [
                    'MaSV' => $diemThi->MaSV,
                    'HoTenSV' => $diemThi->sinhVien->HoTenSV,
                    'DiemLyThuyet' => $chiTietDiem['LyThuyet']['Diem'],
                    'DiemThucHanh' => $chiTietDiem['ThucHanh']['Diem'],
                    'DiemDuAn' => $chiTietDiem['DuAn']['Diem'],
                    'DiemTongKet' => $chiTietDiem['TongKet']['Diem'],
                    'TrangThai' => $chiTietDiem['TongKet']['TrangThai']
                ];
            });

        return Excel::download(new DiemThiExport($danhSachDiem), "BangDiem_{$maLop}_{$tenMH}.xlsx");
    }

    public function nhapDiemChoLop(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validatedData = $request->validate([
            'maLop' => 'required|exists:lophoc,MaLop',
            'tenMH' => 'required|exists:monhoc,TenMH'
        ]);

        // Lấy danh sách sinh viên của lớp
        $lop = LopHoc::findOrFail($validatedData['maLop']);
        $tenMH = $validatedData['tenMH'];

        // Lấy danh sách sinh viên trong lớp
        $danhSachSinhVien = $lop->danhSachSinhVien()->with('sinhVien')->get();

        // Lấy điểm của từng sinh viên (nếu có)
        $danhSachSinhVien->transform(function($dssv) use ($tenMH) {
            $diemThi = DiemThi::where('MaSV', $dssv->sinhVien->MaSV)
                ->where('TenMH', $tenMH)
                ->where('MaLop', $dssv->MaLop)
                ->first();

            $dssv->diemThi = $diemThi ? $diemThi->getChiTietDiem() : null;
            return $dssv;
        });

        return view('tochucthi.diemthi.add_point', [
            'lop' => $lop,
            'tenMH' => $tenMH,
            'danhSachSinhVien' => $danhSachSinhVien
        ]);
    }

    public function luuDiemChoLop(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validatedData = $request->validate([
            'maLop' => 'required|exists:lophoc,MaLop',
            'tenMH' => 'required|exists:monhoc,TenMH',
            'diemThi' => 'required|array',
            'diemThi.*.MaSV' => 'required|exists:sinhvien,MaSV',
            'diemThi.*.DiemLyThuyet' => 'required|numeric|min:0|max:100',
            'diemThi.*.DiemThucHanh' => 'required|numeric|min:0|max:100',
            'diemThi.*.DiemDuAn' => 'required|numeric|min:0|max:100',
            'diemThi.*.LanThi' => 'required|integer|min:1'
        ]);

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($validatedData['diemThi'] as $diemSV) {
            try {
                $diemThi = DiemThi::updateOrCreate(
                    [
                        'MaSV' => $diemSV['MaSV'],
                        'TenMH' => $validatedData['tenMH'],
                        'MaLop' => $validatedData['maLop'],
                        'LanThi' => $diemSV['LanThi']
                    ],
                    [
                        'DiemLyThuyet' => $diemSV['DiemLyThuyet'],
                        'DiemThucHanh' => $diemSV['DiemThucHanh'],
                        'DiemDuAn' => $diemSV['DiemDuAn'],
                        'DiemTongKet' => 
                            ($diemSV['DiemLyThuyet'] * 0.5) + 
                            ($diemSV['DiemThucHanh'] * 0.3) + 
                            ($diemSV['DiemDuAn'] * 0.2),
                        'TrangThai' => 
                            (($diemSV['DiemLyThuyet'] * 0.5) + 
                            ($diemSV['DiemThucHanh'] * 0.3) + 
                            ($diemSV['DiemDuAn'] * 0.2)) >= 50.0 ? 'DatChuan' : 'ChuaDatChuan'
                    ]
                );

                $successCount++;
            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = "Lỗi khi lưu điểm cho sinh viên {$diemSV['MaSV']}: " . $e->getMessage();
            }
        }

        // Trả về kết quả
        return response()->json([
            'success' => true,
            'message' => "Đã lưu điểm thành công $successCount sinh viên" . 
                         ($errorCount > 0 ? ", có $errorCount sinh viên gặp lỗi" : ""),
            'errors' => $errors
        ]);
    }
}