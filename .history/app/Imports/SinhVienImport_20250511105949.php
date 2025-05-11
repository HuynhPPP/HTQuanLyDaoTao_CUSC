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
            if ($row->filter()->isEmpty())
                continue; // Bỏ qua dòng trống
                \Log::info('Row headers:', $row->keys()->toArray());
            // Chuẩn hoá key để tránh lỗi tên cột sai do khoảng trắng
            $row = $row->mapWithKeys(function ($item, $key) {
                return [trim($key) => $item];
            });

            $validator = Validator::make($row->toArray(), [
                'MaSV' => 'required|unique:sinhvien,MaSV',
                'HoTen' => 'required|string',
                'NgaySinh' => 'required|date',
                'GioiTinh' => 'required|in:Nam,Nữ',
                'SoCCCD' => 'required',
                'Email' => 'required|email|unique:sinhvien,Email',
                'Sdt' => 'required',
                'DiaChi' => 'required',
            ]);

            if ($validator->fails()) {
                $errors[] = "Dòng " . ($index + 2) . ": " . implode(', ', $validator->errors()->all());
                continue;
            }

            SinhVien::create([
                'MaSV' => $row['MaSV'],
                'HoTen' => $row['HoTen'],
                'NgaySinh' => $row['NgaySinh'],
                'GioiTinh' => strtolower($row['GioiTinh']) == 'Nam' ? 1 : 0,
                'SoCCCD' => $row['SoCCCD'],
                'Email' => $row['Email'],
                'Sdt' => $row['Sdt'],
                'DiaChi' => $row['DiaChi'],
            ]);

            $successCount++;
        }

        session()->flash('import_success_count', $successCount);
        session()->flash('import_errors', $errors);
    }
}
