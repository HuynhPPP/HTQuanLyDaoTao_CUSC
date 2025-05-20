<?php

namespace App\Imports;

use App\Models\sinhvien;
use App\Models\danhsachsv;
use App\Models\lophoc;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SinhVienImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                // Chuẩn hóa dữ liệu
                $gioiTinh = strtolower(trim($row['gioitinh']));
                $gioiTinhValue = ($gioiTinh == 'nam') ? 1 : 0;
                
                // Chuẩn hóa ngày tháng
                $ngaySinh = $this->formatDate($row['ngaysinh']);
                
                // Tạo sinh viên mới với thông tin cơ bản
                $sinhVien = sinhvien::create([
                    'MaSV' => $row['masv'],
                    'HoTen' => $row['hoten'],
                    'NgaySinh' => $ngaySinh,
                    'GioiTinh' => $gioiTinhValue,
                    'SoCCCD' => $row['socccd'],
                    'Email' => $row['email'],
                    'Sdt' => $row['sdt'],
                    'DiaChi' => $row['diachi'] ?? null,
                    'NgayDangKi' => now(),
                ]);

                // Nếu có thông tin lớp học trong file Excel
                if (isset($row['malop'])) {
                    // Kiểm tra lớp học có tồn tại
                    $lopHoc = lophoc::where('MaLop', $row['malop'])->first();
                    if ($lopHoc) {
                        danhsachsv::create([
                            'MaLop' => $row['malop'],
                            'MaSV' => $sinhVien->MaSV
                        ]);
                    } else {
                        throw new \Exception("Mã lớp {$row['malop']} không tồn tại trong hệ thống");
                    }
                }
            }

            DB::commit();
            session(['import_success_count' => count($rows)]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    private function formatDate($date)
    {
        if (!$date) return null;
        
        try {
            if (is_numeric($date)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date));
            }
            return Carbon::parse($date);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'masv' => 'required|unique:SinhVien,MaSV',
            'hoten' => 'required',
            'ngaysinh' => 'required',
            'gioitinh' => 'required|in:nam,nữ,Nam,Nữ',
            'socccd' => 'required',
            'email' => 'required|email',
            'sdt' => ['required', 'regex:/^0(3|5|7|8|9)[0-9]{8}$/'],
            'malop' => 'nullable|exists:LopHoc,MaLop'
        ];
    }

    public function customValidationMessages()
    {
        return [
            'masv.required' => 'Mã sinh viên là bắt buộc',
            'masv.unique' => 'Mã sinh viên đã tồn tại',
            'hoten.required' => 'Họ tên là bắt buộc',
            'ngaysinh.required' => 'Ngày sinh là bắt buộc',
            'gioitinh.required' => 'Giới tính là bắt buộc',
            'gioitinh.in' => 'Giới tính phải là Nam hoặc Nữ',
            'socccd.required' => 'Số CCCD là bắt buộc',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'sdt.required' => 'Số điện thoại là bắt buộc',
            'sdt.regex' => 'Số điện thoại không hợp lệ',
            'malop.exists' => 'Mã lớp không tồn tại trong hệ thống',
        ];
    }
}
