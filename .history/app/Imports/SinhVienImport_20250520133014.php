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
        // Lấy danh sách lớp học hiện có
        $lopHocs = lophoc::all()->pluck('MaLop')->toArray();
        $countLopHoc = count($lopHocs);
        
        if ($countLopHoc == 0) {
            throw new \Exception('Không có lớp học nào trong hệ thống');
        }

        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                // Chuẩn hóa dữ liệu
                $gioiTinh = strtolower(trim($row['gioitinh']));
                $gioiTinhValue = ($gioiTinh == 'nam') ? 1 : 0;
                
                // Chuẩn hóa ngày tháng
                $ngaySinh = $this->formatDate($row['ngaysinh']);
                $ngayCap = isset($row['ngaycap']) ? $this->formatDate($row['ngaycap']) : null;
                $ngayDangKi = isset($row['ngaydangki']) ? $this->formatDate($row['ngaydangki']) : now();

                // Tạo sinh viên mới
                $sinhVien = sinhvien::create([
                    'MaSV' => $row['masv'],
                    'MaEnroll' => $row['maenroll'] ?? null,
                    'HoTen' => $row['hoten'],
                    'NgaySinh' => $ngaySinh,
                    'GioiTinh' => $gioiTinhValue,
                    'SoCCCD' => $row['socccd'],
                    'NgayCap' => $ngayCap,
                    'NoiCap' => $row['noicap'] ?? null,
                    'Sdt' => $row['sdt'],
                    'NoiSinh' => $row['noisinh'] ?? null,
                    'DiaChi' => $row['diachi'] ?? null,
                    'Email' => $row['email'],
                    'EmailCUSC' => $row['emailcusc'] ?? null,
                    'Zalo' => $row['zalo'] ?? null,
                    'HoTenNguoiThan' => $row['hotennguoithan'] ?? null,
                    'MoiQuanHe' => $row['moiquanhe'] ?? null,
                    'SdtNguoiThan' => $row['sdtnguoithan'] ?? null,
                    'EmailNguoiThan' => $row['emailnguoithan'] ?? null,
                    'ZaloNguoiThan' => $row['zalonguoithan'] ?? null,
                    'NgayDangKi' => $ngayDangKi,
                    // Các trường tài chính
                    'InDebt' => $row['indebt'] ?? null,
                    'Receipt' => $row['receipt'] ?? null,
                    'Invoice' => $row['invoice'] ?? null,
                    'Billing' => $row['billing'] ?? null,
                    'Coll' => $row['coll'] ?? null,
                    'Billing(VND)' => $row['billingvnd'] ?? null,
                    'Coll(VND)' => $row['collvnd'] ?? null,
                    'Discount' => $row['discount'] ?? null,
                    'LiDo' => $row['lido'] ?? null,
                    'Size' => $row['size'] ?? null,
                ]);

                // Phân bổ sinh viên vào lớp ngẫu nhiên
                $randomLopIndex = array_rand($lopHocs);
                $maLop = $lopHocs[$randomLopIndex];
                
                danhsachsv::create([
                    'MaLop' => $maLop,
                    'MaSV' => $sinhVien->MaSV
                ]);
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
        ];
    }
}
