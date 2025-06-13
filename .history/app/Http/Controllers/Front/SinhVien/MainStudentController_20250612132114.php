<?php

namespace App\Http\Controllers\Front\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MainStudentController extends Controller
{
    public function profile()
    {
        // Lấy thông tin sinh viên từ tài khoản đăng nhập
        $id = session('id');

        if (!$id) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin tài khoản');
        }

        $sinhVien = sinhvien::where('MaSV', $id)->first();

        if (!$sinhVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin sinh viên');
        }

        // Lấy thêm thông tin từ các bảng liên quan nếu cần
        $sinhVien->load([
            'hosotuyensinh',
            'danhSachLop',
            // Thêm các quan hệ khác nếu có
        ]);

        return view('quanly_nhansu.sinhvien.profile', compact('sinhVien'));
    }
    public function updateProfile(Request $request)
    {
        $id = session('id');
        $ldapAccount = LdapAccount::where('MaTaiKhoan', $id)->first();

        if (!$ldapAccount) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin tài khoản');
        }

        $sinhVien = sinhvien::where('MaSV', $id)->first();

        if (!$sinhVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin sinh viên');
        }

        $validated = $request->validate([
            'Email' => 'required|email',
            'Sdt' => 'nullable|string|max:15',
            'DiaChi' => 'nullable|string|max:255',
            'Zalo' => 'nullable|string|max:20',
            'HoTenNguoiThan' => 'nullable|string|max:100',
            'MoiQuanHe' => 'nullable|string|max:50',
            'SdtNguoiThan' => 'nullable|string|max:15',
            'EmailNguoiThan' => 'nullable|email'
        ], [
            'Email.required' => 'Vui lòng nhập email cá nhân',
            'Email.email' => 'Địa chỉ email không hợp lệ',
            'Sdt.max' => 'Số điện thoại không được vượt quá 15 ký tự',
            'DiaChi.max' => 'Địa chỉ không được vượt quá 255 ký tự',
            'EmailNguoiThan.email' => 'Địa chỉ email người thân không hợp lệ'
        ]);

        try {
            $sinhVien->update($validated);

            return redirect()->route('student.profile')
                ->with('success', 'Cập nhật thông tin thành công');
        } catch (\Exception $e) {
            Log::error('Lỗi cập nhật thông tin sinh viên', [
                'MaSV' => $sinhVien->MaSV,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật thông tin');
        }
    }
}
