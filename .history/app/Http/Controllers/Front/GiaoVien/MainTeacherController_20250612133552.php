<?php

namespace App\Http\Controllers\Front\GiaoVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MainTeacherController extends Controller
{
    public function changePassword(Request $request)
    {
        // Lấy thông tin tài khoản LDAP
        $id = session('id');
        $ldapAccount = LdapAccount::where('MaTaiKhoan', $id)->first();

        if (!$ldapAccount) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy tài khoản'
            ], 404);
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
            return redirect()->back()->with([
                'error', 'Lỗi ! '. $validator->errors(),
                'errors' => $validator->errors()
            ], 422);
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
