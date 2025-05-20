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
            if ($row->filter()->isEmpty())
                continue; // Bỏ qua dòng trống

            $validator = Validator::make($row->toArray(), [
                'masv' => 'required|unique:sinhvien,MaSV',
                'hoten' => 'required|string|max:50',
                'ngaysinh' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        try {
                            Carbon::parse($value);
                        } catch (\Exception $e) {
                            $fail('Ngày sinh không hợp lệ. Định dạng nên là YYYY-MM-DD.');
                        }
                    }
                ],
                'gioitinh' => 'required|in:Nam,Nữ',
                'socccd' => 'required|numeric',
                'email' => 'required|email|unique:sinhvien,Email',
                'sdt' => ['required', 'string', 'regex:/^0(3|5|7|8|9)[0-9]{8}$/'],
                'diachi' => 'required|string|max:255',
            ], [
                'masv.required' => 'Mã sinh viên là bắt buộc.',
                'masv.unique' => 'Mã sinh viên đã tồn tại.',
                'hoten.required' => 'Họ tên là bắt buộc.',
                'hoten.max' => 'Họ tên không được vượt quá 50 ký tự.',
                'ngaysinh.required' => 'Ngày sinh là bắt buộc.',
                'gioitinh.required' => 'Giới tính là bắt buộc.',
                'gioitinh.in' => 'Giới tính phải là Nam hoặc Nữ.',
                'socccd.required' => 'Số CCCD là bắt buộc.',
                'socccd.numeric' => 'Số CCCD phải là số.',
                'email.required' => 'Email là bắt buộc.',
                'email.email' => 'Email không đúng định dạng.',
                'email.unique' => 'Email đã tồn tại.',
                'sdt.required' => 'Số điện thoại là bắt buộc.',
                'sdt.regex' => 'Số điện thoại không hợp lệ. Vui lòng nhập số điện thoại Việt Nam (bắt đầu bằng 03, 05, 07, 08, 09).',
                'diachi.required' => 'Địa chỉ là bắt buộc.',
                'diachi.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
            ]);

            if ($validator->fails()) {
                $errors[] = "Dòng " . ($index + 2) . ": " . implode(', ', $validator->errors()->all());
                continue;
            }

            try {
                // Lưu dữ liệu sinh viên
                $sinhVien = SinhVien::create([
                    'MaSV' => $row['masv'],
                    'HoTen' => $row['hoten'],
                    'NgaySinh' => Carbon::parse($row['ngaysinh'])->format('Y-m-d'),
                    'GioiTinh' => $row['gioitinh'] === 'Nam' ? 1 : 0,
                    'SoCCCD' => $row['socccd'],
                    'Email' => $row['email'],
                    'Sdt' => $row['sdt'],
                    'DiaChi' => $row['diachi'],
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
