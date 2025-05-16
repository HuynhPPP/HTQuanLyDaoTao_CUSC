<?php

namespace App\Imports;

use App\Models\giaovien;
use App\Models\hocvi;
use App\Models\bangcapcanbo;
use App\Models\chucvu;
use App\Models\donvi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class GiaoVienImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $errors = [];
        $successCount = 0;

        foreach ($rows as $index => $row) {
            if ($row->filter()->isEmpty())
                continue; // Bỏ qua dòng trống

            $validator = Validator::make($row->toArray(), [
                'magv' => 'required|unique:giaovien,MaGV',
                'hotengv' => 'required|string',
                'gioitinh' => 'required|in:Nam,Nữ',
                'email' => 'required|email|unique:giaovien,Email',
                'loaigv' => 'required|in:CoHuu,MoiGiang',
            ], [
                'magv.required' => 'Mã giáo viên là bắt buộc.',
                'magv.unique' => 'Mã giáo viên đã tồn tại.',
                'hotengv.required' => 'Họ tên giáo viên là bắt buộc.',
                'gioitinh.required' => 'Giới tính là bắt buộc.',
                'gioitinh.in' => 'Giới tính phải là Nam hoặc Nữ.',
                'email.required' => 'Email là bắt buộc.',
                'email.email' => 'Email không đúng định dạng.',
                'email.unique' => 'Email đã tồn tại.',
                'loaigv.required' => 'Loại giáo viên là bắt buộc.',
                'loaigv.in' => 'Loại giáo viên phải là Cơ hữu hoặc Mời giảng.',
            ]);

            if ($validator->fails()) {
                $errors[] = "Dòng " . ($index + 2) . ": " . implode(', ', $validator->errors()->all());
                continue;
            }

            try {
                // Xử lý Học Vị
                $maHV = null;
                if (!empty($row['mahv'])) {
                    $hocVi = hocvi::firstOrCreate(
                        ['MaHV' => $row['mahv']],
                        ['TenHocVi' => $row['tenhocvi'] ?? 'Học vị ' . $row['mahv']]
                    );
                    $maHV = $hocVi->MaHV;
                }

                // Xử lý Chức Vụ
                $tenChucVu = null;
                if (!empty($row['tenchucvu'])) {
                    $chucVu = chucvu::firstOrCreate(
                        ['TenChucVu' => $row['tenchucvu']]
                    );
                    $tenChucVu = $chucVu->TenChucVu;
                }

                // Xử lý Đơn Vị
                $maDV = null;
                if (!empty($row['madv'])) {
                    $donVi = donvi::firstOrCreate(
                        ['MaDV' => $row['madv']],
                        ['TenDVHienTai' => $row['tendvhientai'] ?? 'Đơn vị ' . $row['madv']]
                    );
                    $maDV = $donVi->MaDV;
                }

                // Xử lý Bằng Cấp
                $maBang = null;
                if (!empty($row['mabang'])) {
                    $bangCap = bangcapcanbo::firstOrCreate(
                        ['MaBang' => $row['mabang']],
                        ['TenBang' => $row['tenbang'] ?? 'Bằng cấp ' . $row['mabang']]
                    );
                    $maBang = $bangCap->MaBang;
                }

                // Lưu dữ liệu Giáo Viên
                $giaoVien = giaovien::create([
                    'MaGV' => $row['magv'],
                    'HoTenGV' => $row['hotengv'],
                    'GioiTinh' => strtolower($row['gioitinh']) === 'nam' ? 1 : 0,
                    'Email' => $row['email'],
                    'Sdt' => $row['sdt'] ?? null,
                    'LoaiGV' => $row['loaigv'],
                    'MaHV' => $maHV,
                    'TenChucVu' => $tenChucVu,
                    'MaDV' => $maDV,
                    'MaBang' => $maBang,
                    'ChuyenNganh' => $row['chuyennganh'] ?? null,
                    'NgayBatDauCongTac' => $row['ngaybatdaucongtac'] ? Carbon::parse($row['ngaybatdaucongtac'])->format('Y-m-d') : null,
                    'GhiChu' => $row['ghichu'] ?? null,
                ]);

                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Dòng " . ($index + 2) . ": Lỗi - " . $e->getMessage();
            }
        }

        // Lưu thông báo session cho controller hiển thị lại
        session()->flash('import_success_count', $successCount);
        session()->flash('import_errors', $errors);
    }
} 