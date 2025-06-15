<?php

namespace App\Imports;

use App\Models\SinhVien;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class SinhVienImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $errors = [];
        $successCount = 0;

        foreach ($rows as $index => $row) {
            if ($row->filter()->isEmpty()) continue;

            $validator = Validator::make($row->toArray(), [
                'masv' => 'required|unique:sinhvien,MaSV',
                'email' => 'nullable|email|unique:sinhvien,Email',
                'hoten' => 'nullable|string|max:30',
                'ngaysinh' => 'nullable|date',
                'gioitinh' => 'nullable|in:Nam,Nữ,1,0',
                'socccd' => 'nullable|numeric',
                'sdt' => ['nullable', 'string', 'regex:/^0(3|5|7|8|9)[0-9]{8}$/'],
                'diachi' => 'nullable|string|max:255',
                'emailcusc' => 'nullable|email',
                'emailnguoithan' => 'nullable|email',
                'zalo' => 'nullable|numeric',
                'zalonguoithan' => 'nullable|numeric',
                'sdtnguoithan' => 'nullable|numeric',
                'discount' => 'nullable|numeric|min:0|max:1',
                'billing' => 'nullable|numeric',
                'coll' => 'nullable|numeric',
                'billing(vnd)' => 'nullable|numeric',
                'coll(vnd)' => 'nullable|numeric',
                'ngaydangki' => 'nullable|date',
                'ngaycap' => 'nullable|date',
                'tinhtranghoctap' => 'nullable|in:DangHoc,DaTotNghiep,DaNghiHoc',
            ], [
                'masv.required' => 'Mã sinh viên là bắt buộc.',
                'masv.unique' => 'Mã sinh viên đã tồn tại.',
                'email.email' => 'Email không đúng định dạng.',
                'email.unique' => 'Email đã tồn tại.',
                'discount.max' => 'Giảm giá phải từ 0 đến 1.',
                'sdt.regex' => 'Số điện thoại không hợp lệ.',
            ]);

            if ($validator->fails()) {
                $errors[] = "Dòng " . ($index + 2) . ": " . implode(', ', $validator->errors()->all());
                continue;
            }

            try {
                SinhVien::create([
                    'MaSV' => $row['masv'],
                    'MaEnroll' => $row['maenroll'] ?? null,
                    'HoTen' => $row['hoten'] ?? null,
                    'InDebt' => $row['indebt'] ?? null,
                    'NgaySinh' => !empty($row['ngaysinh']) ? Carbon::parse($row['ngaysinh']) : null,
                    'GioiTinh' => in_array($row['gioitinh'], ['Nam', '1']) ? 1 : (in_array($row['gioitinh'], ['Nữ', '0']) ? 0 : null),
                    'SoCCCD' => $row['socccd'] ?? null,
                    'NgayCap' => !empty($row['ngaycap']) ? Carbon::parse($row['ngaycap']) : null,
                    'NoiCap' => $row['noicap'] ?? null,
                    'Sdt' => $row['sdt'] ?? null,
                    'NoiSinh' => $row['noisinh'] ?? null,
                    'DiaChi' => $row['diachi'] ?? null,
                    'Zalo' => $row['zalo'] ?? null,
                    'Receipt' => $row['receipt'] ?? null,
                    'Invoice' => $row['invoice'] ?? null,
                    'Billing' => $row['billing'] ?? null,
                    'Coll' => $row['coll'] ?? null,
                    'Billing(VND)' => $row['billing(vnd)'] ?? null,
                    'Coll(VND)' => $row['coll(vnd)'] ?? null,
                    'Discount' => $row['discount'] ?? null,
                    'LiDo' => $row['lido'] ?? null,
                    'NgayDangKi' => !empty($row['ngaydangki']) ? Carbon::parse($row['ngaydangki']) : null,
                    'HoTenNguoiThan' => $row['hotennguoithan'] ?? null,
                    'MoiQuanHe' => $row['moiquanhe'] ?? null,
                    'SdtNguoiThan' => $row['sdtnguoithan'] ?? null,
                    'ZaloNguoiThan' => $row['zalonguoithan'] ?? null,
                    'EmailNguoiThan' => $row['emailnguoithan'] ?? null,
                    'Email' => $row['email'] ?? null,
                    'EmailCUSC' => $row['emailcusc'] ?? null,
                    'password_CUSC' => $row['password_cusc'] ?? null,
                    'Size' => $row['size'] ?? null,
                    'TinhTrangHocTap' => $row['tinhtranghoctap'] ?? null,
                ]);

                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Dòng " . ($index + 2) . ": Lỗi - " . $e->getMessage();
            }
        }

        session()->flash('import_success_count', $successCount);
        session()->flash('import_errors', $errors);
    }
}