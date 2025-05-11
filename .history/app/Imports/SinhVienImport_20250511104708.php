<?php

namespace App\Imports;

use App\Models\SinhVien;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SinhVienImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $errors = [];
        $successCount = 0;

        foreach ($rows as $index => $row) {
            if ($row->filter()->isEmpty()) continue; // bỏ qua dòng trống

            $validator = Validator::make($row->toArray(), [
                'MaSV '      => 'required|unique:sinhvien,MaSV',
                'hoten'     => 'required|string',
                'ngaysinh'  => 'required|date',
                'gioitinh'  => 'required|in:Nam,Nữ',
                'socccd'    => 'required',
                'email'     => 'required|email|unique:sinhvien,Email',
                'sdt'       => 'required',
                'diachi'    => 'required',
            ]);

            if ($validator->fails()) {
                $errors[] = "Dòng " . ($index + 2) . ": " . implode(', ', $validator->errors()->all());
                continue;
            }

            SinhVien::create([
                'MaSV'      => $row['masv'],
                'HoTen'     => $row['hoten'],
                'NgaySinh'  => $row['ngaysinh'],
                'GioiTinh'  => strtolower($row['gioitinh']) == 'Nam' ? 1 : 0,
                'SoCCCD'    => $row['socccd'],
                'Email'     => $row['email'],
                'Sdt'       => $row['sdt'],
                'DiaChi'    => $row['diachi'],
            ]);

            $successCount++;
        }

        session()->flash('import_success_count', $successCount);
        session()->flash('import_errors', $errors);
    }
}
