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
            if ($row->filter()->isEmpty()) continue; // Bỏ qua dòng trống

            $validator = Validator::make($row->toArray(), [
                'masv'      => 'required|unique:sinhvien,MaSV',
                'hoten'     => 'required|string',
                'ngaysinh'  => ['required', function ($attribute, $value, $fail) {
                    try {
                        Carbon::parse($value);
                    } catch (\Exception $e) {
                        $fail('Ngày sinh không hợp lệ. Định dạng nên là YYYY-MM-DD.');
                    }
                }],
                'gioitinh'  => 'required|in:Nam,Nữ',
                'socccd'    => 'required',
                'email'     => 'required|email|unique:sinhvien,Email',
                'sdt'       => 'required',
                'diachi'    => 'required',
            ], [
                'masv.required'     => 'Mã sinh viên là bắt buộc.',
                'masv.unique'       => 'Mã sinh viên đã tồn tại.',
                'hoten.required'    => 'Họ tên là bắt buộc.',
                'ngaysinh.required' => 'Ngày sinh là bắt buộc.',
                'gioitinh.required' => 'Giới tính là bắt buộc.',
                'gioitinh.in'       => 'Giới tính phải là Nam hoặc Nữ.',
                'socccd.required'   => 'Số CCCD là bắt buộc.',
                'email.required'    => 'Email là bắt buộc.',
                'email.email'       => 'Email không đúng định dạng.',
                'email.unique'      => 'Email đã tồn tại.',
                'sdt.required'      => 'Số điện thoại là bắt buộc.',
                'diachi.required'   => 'Địa chỉ là bắt buộc.',
            ]);

            if ($validator->fails()) {
                $errors[] = "Dòng " . ($index + 2) . ": " . implode(', ', $validator->errors()->all());
                continue;
            }

            // Lưu dữ liệu nếu hợp lệ
            SinhVien::create([
                'MaSV'     => $row['masv'],
                'HoTen'    => $row['hoten'],
                'NgaySinh'=> Carbon::parse($row['ngaysinh'])->format('Y-m-d'),
                'GioiTinh'=> strtolower($row['gioitinh']) === 'nam' ? 1 : 0,
                'SoCCCD'   => $row['socccd'],
                'Email'    => $row['email'],
                'Sdt'      => $row['sdt'],
                'DiaChi'   => $row['diachi'],
            ]);

            $successCount++;
        }

        // Lưu thông báo session cho controller hiển thị lại
        session()->flash('import_success_count', $successCount);
        session()->flash('import_errors', $errors);
    }
}
