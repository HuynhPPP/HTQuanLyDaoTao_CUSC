<?php

namespace App\Http\Controllers\Front\SinhVien;

use App\Http\Controllers\Controller;
use App\Models\sinhvien;
use App\Models\LdapAccount;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
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
            'danhSachLop',
            // Thêm các quan hệ khác nếu có
        ]);

        return view('frontend.sinhvien.thong_tin_sinh_vien.profile', compact('sinhVien'));
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
    public function changePassword(Request $request)
    {
        // Lấy thông tin tài khoản LDAP
        $id = session('id');
        $ldapAccount = LdapAccount::where('MaTaiKhoan', $id)->first();

        if (!$ldapAccount) {
            return redirect()->back()->with('error', 'Không tìm thấy tài khoản');
        }

        // Validate dữ liệu
        $validator = \Validator::make($request->all(), [
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) use ($ldapAccount) {
                    // So sánh trực tiếp chuỗi mật khẩu
                    if ($value !== $ldapAccount->initial_password) {
                        $fail('Mật khẩu hiện tại không chính xác');
                    }
                }
            ],
            'new_password' => 'required|min:8|different:current_password',
            'confirm_password' => 'required|same:new_password'
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự',
            'new_password.different' => 'Mật khẩu mới không được trùng mật khẩu hiện tại',
            'confirm_password.required' => 'Vui lòng xác nhận mật khẩu mới',
            'confirm_password.same' => 'Xác nhận mật khẩu không khớp'
        ]);

        // Kiểm tra lỗi
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Lỗi ! Thông tin chưa chính xác. Vui lòng kiểm tra lại');
        }

        try {
            // Mã hóa mật khẩu mới và lưu lại
            $ldapAccount->initial_password = Hash::make($request->new_password);
            $ldapAccount->save();

            return redirect()->back()->with('success', 'Đổi mật khẩu thành công');
        } catch (\Exception $e) {
            \Log::error('Lỗi đổi mật khẩu: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi. Vui lòng thử lại sau.'
            ], 500);
        }
    }
}
