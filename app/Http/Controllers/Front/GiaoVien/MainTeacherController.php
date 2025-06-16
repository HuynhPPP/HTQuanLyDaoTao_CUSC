<?php

namespace App\Http\Controllers\Front\GiaoVien;

use App\Http\Controllers\Controller;
use App\Models\giaovien;
use App\Models\hocvi;
use App\Models\bangcapcanbo;
use App\Models\chucvu;
use App\Models\donvi;
use App\Models\LdapAccount;
use App\Imports\GiaoVienImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Mail\LdapAccountInfoMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MainTeacherController extends Controller
{
    public function profile()
    {
        // Lấy thông tin giáo viên từ tài khoản đăng nhập
        $id = session('id');

        if (!$id) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin tài khoản');
        }

        $giaoVien = giaovien::where('MaGV', $id)->first();

        if (!$giaoVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giáo viên');
        }

        // Lấy danh sách các danh mục để hiển thị
        $hocvis = hocvi::all();
        $chucvus = chucvu::all();
        $donvis = donvi::all();

        return view('quanly_nhansu.giaovien.profile', compact(
            'giaoVien',
            'hocvis',
            'chucvus',
            'donvis'
        ));
    }
    public function updateProfile(Request $request)
    {
        $username = session('user');
        $ldapAccount = LdapAccount::where('username', $username)->first();

        if (!$ldapAccount) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin tài khoản');
        }

        $giaoVien = giaovien::where('MaGV', $ldapAccount->MaTaiKhoan)->first();

        if (!$giaoVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giáo viên');
        }

        $validated = $request->validate([
            'Email' => 'required|email',
            'Sdt' => 'nullable|string|max:15',
            'MaHV' => 'nullable|exists:hocvi,MaHV',
            'TenChucVu' => 'nullable|exists:chucvu,TenChucVu',
            'MaDV' => 'nullable|exists:donvi,MaDV',
            'ChuyenNganh' => 'nullable|string|max:100',
            'GhiChu' => 'nullable|string'
        ]);

        try {
            $giaoVien->update($validated);

            return redirect()->route('giaovien.profile')
                ->with('success', 'Cập nhật thông tin thành công');
        } catch (\Exception $e) {
            Log::error('Lỗi cập nhật thông tin giáo viên', [
                'MaGV' => $giaoVien->MaGV,
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
                'error',
                'Lỗi ! ' . $validator->errors(),
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
    public function teacherSchedule()
    {
        $id = session('id');
        $giaoVien = \App\Models\giaovien::where('MaGV', $id)->first();
        $giangDays = \App\Models\GiangDay::with(['monHoc', 'lopHoc'])
            ->where('MaGV', $giaoVien->MaGV)
            ->get();
        $monHocTheoLop = $giangDays->groupBy(function($item) {
            return $item->monHoc->MaMH;
        });
        return view('teacher_schedule', compact('giaoVien', 'monHocTheoLop'));
    }
}
