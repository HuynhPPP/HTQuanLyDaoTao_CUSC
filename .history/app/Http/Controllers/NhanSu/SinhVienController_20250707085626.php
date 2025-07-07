<?php

namespace App\Http\Controllers\NhanSu;
use App\Http\Controllers\Controller;

use App\Models\sinhvien;
use App\Models\hoso;
use App\Models\danhsachsv;
use App\Models\LdapAccount;
use App\Mail\LdapAccountInfoMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SinhVienImport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use LdapRecord\Connection;
use LdapRecord\Models\ActiveDirectory\User;
use LdapRecord\Models\ActiveDirectory\Group;
use LdapRecord\Container;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SinhVienController extends Controller
{
    public function index()
    {
        $sinhViens = sinhvien::with(['danhSachLop'])->get();
        return view('quanly_nhansu.sinhvien.index', compact('sinhViens'));
    }
    public function create()
    {
        return view('quanly_nhansu.sinhvien.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'MaSV' => 'required|unique:SinhVien,MaSV',
            'HoTen' => 'required',
            'NgaySinh' => 'required|date',
            'GioiTinh' => 'required',
            'SoCCCD' => 'required|numeric',
            'Email' => 'required|email',
            'Sdt' => ['required', 'string', 'regex:/^0(3|5|7|8|9)[0-9]{8}$/'],
        ], [
            'MaSV.required' => 'Vui lòng nhập mã sinh viên.',
            'MaSV.unique' => 'Mã sinh viên đã tồn tại trong hệ thống.',
            'HoTen.required' => 'Vui lòng nhập họ và tên.',
            'NgaySinh.required' => 'Vui lòng nhập ngày sinh.',
            'NgaySinh.date' => 'Ngày sinh không đúng định dạng.',
            'GioiTinh.required' => 'Vui lòng chọn giới tính.',
            'SoCCCD.required' => 'Vui lòng nhập số CCCD.',
            'SoCCCD.numeric' => 'Số CCCD phải là số.',
            'Email.required' => 'Vui lòng nhập email.',
            'Email.email' => 'Email không đúng định dạng.',
            'Sdt.required' => 'Vui lòng nhập số điện thoại.',
            'Sdt.regex' => 'Số điện thoại không hợp lệ. Vui lòng nhập số điện thoại Việt Nam (bắt đầu bằng 03, 05, 07, 08, 09).',
        ]);

        // Mapping dữ liệu
        $data = $request->all();
        $data['GioiTinh'] = $request->GioiTinh === 'Nam' ? 1 : 0;

        DB::beginTransaction();
        try {
            $sinhVien = sinhvien::create($data);
            // ... các thao tác khác
            DB::commit();
            return redirect()->route('student.list')->with('success', 'Thêm sinh viên thành công');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new SinhVienImport, $request->file('file'));

        $successCount = session('import_success_count', 0);
        $errors = session('import_errors', []);

        if ($successCount > 0 && empty($errors)) {
            return back()->with('success', "Đã import thành công $successCount sinh viên.");
        } elseif ($successCount > 0 && !empty($errors)) {
            return redirect()->route('student.list')->with([
                'success' => "Import thành công $successCount dòng, nhưng có lỗi ở các dòng khác.",
                'import_errors' => $errors
            ]);
        } else {
            return back()->with([
                'error' => "Import thất bại. Không có dòng nào được lưu. Chi tiết lỗi: " . implode(', ', $errors),
                'import_errors' => $errors,
                // dd(session('import_errors')), // Xem chi tiết lỗi

            ]);
        }
    }
    public function show($maSV)
    {
        $sinhVien = sinhvien::with(['hosotuyensinh', 'danhSachLop'])
            ->where('MaSV', $maSV)
            ->firstOrFail();
        return view('quanly_nhansu.sinhvien.show', compact('sinhVien'));
    }
    public function edit($maSV)
    {
        $sinhVien = sinhvien::where('MaSV', $maSV)->firstOrFail();
        return view('quanly_nhansu.sinhvien.edit', compact('sinhVien'));
    }
    public function edit_all($maSV)
    {
        $sinhVien = sinhvien::where('MaSV', $maSV)->firstOrFail();
        return view('quanly_nhansu.sinhvien.edit_all', compact('sinhVien'));
    }
    public function update(Request $request, $maSV)
    {
        dd($request->all());
        $request->validate([
            'MaSV' => 'required|unique:sinhvien,MaSV,' . $maSV . ',MaSV',
            'HoTen' => 'required',
            'NgaySinh' => 'required|date',
            'GioiTinh' => 'required',
            'SoCCCD' => 'required|numeric',
            'Email' => 'required|email',
            'Sdt' => ['required', 'string', 'regex:/^0(3|5|7|8|9)[0-9]{8}$/'],
        ], [
            'MaSV.required' => 'Vui lòng nhập mã sinh viên.',
            'MaSV.unique' => 'Mã sinh viên đã tồn tại trong hệ thống.',
            'HoTen.required' => 'Vui lòng nhập họ và tên.',
            'NgaySinh.required' => 'Vui lòng nhập ngày sinh.',
            'NgaySinh.date' => 'Ngày sinh không đúng định dạng.',
            'GioiTinh.required' => 'Vui lòng chọn giới tính.',
            'SoCCCD.required' => 'Vui lòng nhập số CCCD.',
            'SoCCCD.numeric' => 'Số CCCD phải là số.',
            'Email.required' => 'Vui lòng nhập email.',
            'Email.email' => 'Email không đúng định dạng.',
            'Sdt.required' => 'Vui lòng nhập số điện thoại.',
            'Sdt.regex' => 'Số điện thoại không hợp lệ. Vui lòng nhập số điện thoại Việt Nam.',
        ]);

        $sinhVien = sinhvien::where('MaSV', $maSV)->firstOrFail();
        $sinhVien->update([
            'MaSV' => $request->MaSV,
            'HoTen' => $request->HoTen,
            'NgaySinh' => $request->NgaySinh,
            'GioiTinh' => $request->GioiTinh,
            'SoCCCD' => $request->SoCCCD,
            'Email' => $request->Email,
            'Sdt' => $request->Sdt,
            'DiaChi' => $request->DiaChi,
        ]);

        return redirect()->route('student.show', $sinhVien->MaSV)->with('success', 'Cập nhật thông tin sinh viên thành công');
    }
    public function update_all(Request $request, $id)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'MaSV' => 'required',
            'HoTen' => 'required',
            'NgaySinh' => 'required|date',
            'GioiTinh' => 'required|in:0,1',
            'SoCCCD' => 'required',
            'Email' => 'required|email',
            'Sdt' => 'required',
        ]);

        // Tìm sinh viên cần cập nhật
        $sinhVien = SinhVien::findOrFail($id);

        // Cập nhật thông tin cá nhân
        $sinhVien->update([
            'MaSV' => $request->MaSV,
            'HoTen' => $request->HoTen,
            'NgaySinh' => $request->NgaySinh,
            'GioiTinh' => $request->GioiTinh,
            'SoCCCD' => $request->SoCCCD,
            'NgayCap' => $request->NgayCap,
            'NoiCap' => $request->NoiCap,
            'TinhTrangHocTap' => $request->TinhTrangHocTap,

            // Thông tin liên hệ
            'Email' => $request->Email,
            'EmailCUSC' => $request->EmailCUSC,
            'Sdt' => $request->Sdt,
            'DiaChi' => $request->DiaChi,
            'Zalo' => $request->Zalo,

            // Thông tin người thân
            'HoTenNguoiThan' => $request->HoTenNguoiThan,
            'MoiQuanHe' => $request->MoiQuanHe,
            'SdtNguoiThan' => $request->SdtNguoiThan,
            'EmailNguoiThan' => $request->EmailNguoiThan,
            'ZaloNguoiThan' => $request->ZaloNguoiThan,
        ]);

        return redirect()->route('student.list')
            ->with('success', 'Cập nhật thông tin sinh viên thành công');
    }
    public function destroy($maSV)
    {
        DB::beginTransaction();
        try {
            $sinhVien = sinhvien::where('MaSV', $maSV)->firstOrFail();

            // Xóa các bản ghi liên quan
            hoso::where('MaSV', $maSV)->delete();
            danhsachsv::where('MaSV', $maSV)->delete();

            // Xóa sinh viên
            $sinhVien->delete();

            DB::commit();
            return redirect()->route('student.list')->with('success', 'Xóa sinh viên thành công');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    public function dongBoTaiKhoanLDAP()
    {
        $sinhViens = SinhVien::whereNull('EmailCUSC')->get();
        $successCount = 0;
        $errorCount = 0;
        $errorDetails = [];

        foreach ($sinhViens as $sinhVien) {
            DB::beginTransaction();
            try {
                // Tạo email và mật khẩu
                $email = $this->taoEmailCUSC($sinhVien);
                $password = $this->taoMatKhauManh();
                $fullEmail = $email . '@cusc.ctu.vn';

                // Tạo tài khoản trong bảng ldap_accounts
                $ldapAccount = LdapAccount::create([
                    'MaTaiKhoan' => $sinhVien->MaSV,
                    'username' => $email,
                    'email' => $fullEmail,
                    'full_name' => $sinhVien->HoTen,
                    'initial_password' => $password,
                    'role' => 'student',
                    'is_sent' => false,
                    'is_active' => true
                ]);

                // Cập nhật cột EmailCUSC trong bảng sinhvien
                $sinhVien->update([
                    'EmailCUSC' => $fullEmail
                ]);

                DB::commit();
                $successCount++;

            } catch (\Exception $e) {
                DB::rollBack();
                $errorCount++;
                $errorDetails[] = [
                    'MaTaiKhoan' => $sinhVien->MaSV,
                    'ho_ten' => $sinhVien->HoTen,
                    'error_message' => $e->getMessage()
                ];
            }
        }

        $message = "Đồng bộ hoàn tất. Thành công: $successCount, Lỗi: $errorCount";
        return redirect()->route('student.list')
            ->with('success', $message)
            ->with('error_details', $errorDetails);
    }
    private function taoMatKhauManh($length = 6)
    {
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $digits = '0123456789';

        // Đảm bảo có ít nhất 1 ký tự từ mỗi nhóm
        $password = [
            $upper[random_int(0, strlen($upper) - 1)],
            $lower[random_int(0, strlen($lower) - 1)],
            $digits[random_int(0, strlen($digits) - 1)],
        ];

        // Thêm các ký tự ngẫu nhiên khác
        $all = $upper . $lower . $digits;
        for ($i = 4; $i < $length; $i++) {
            $password[] = $all[random_int(0, strlen($all) - 1)];
        }

        // Xáo trộn mật khẩu để ngẫu nhiên hơn
        shuffle($password);

        return implode('', $password);
    }
    public function xuatDanhSachTaiKhoanMoi()
    {
        $ldapAccounts = LdapAccount::orderBy('created_at', 'desc')
            ->where('role', 'student')
            ->get();

        return view('quanly_nhansu.sinhvien.list_account', compact('ldapAccounts'));
    }
    public function guiThongTinTaiKhoan($id)
    {
        $ldapAccount = LdapAccount::findOrFail($id);

        // Lấy user theo mã tài khoản
        $user = $ldapAccount->getUser();

        if (!$user) {
            return redirect()->back()->with('error', 'Không tìm thấy người dùng tương ứng.');
        }

        try {
            // Gửi email theo email của user
            Mail::to($user->Email)->send(new LdapAccountInfoMail($ldapAccount));

            // Đánh dấu đã gửi
            $ldapAccount->update(['is_sent' => true]);

            return redirect()->back()->with('success', 'Đã gửi thông tin tài khoản thành công.');
        } catch (\Exception $e) {
            Log::error('Lỗi gửi email LDAP', [
                'ma_tai_khoan' => $ldapAccount->MaTaiKhoan,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Không thể gửi email: ' . $e->getMessage());
        }
    }
    public function guiThongTinTaiKhoanHangLoat(Request $request)
    {
        $accountIds = $request->input('accounts', []);
        $sentAccounts = [];
        $errors = [];

        foreach ($accountIds as $id) {
            try {
                $ldapAccount = LdapAccount::findOrFail($id);

                // Kiểm tra nếu đã gửi rồi thì bỏ qua
                if ($ldapAccount->is_sent) {
                    continue;
                }

                $user = $ldapAccount->getUser();

                if (!$user || !$user->Email) {
                    $errors[] = "Không tìm thấy email cho tài khoản {$ldapAccount->MaTaiKhoan}";
                    continue;
                }

                // Gửi email
                Mail::to($user->Email)->send(new LdapAccountInfoMail($ldapAccount));

                // Đánh dấu đã gửi
                $ldapAccount->update(['is_sent' => true]);
                $sentAccounts[] = $id;

            } catch (\Exception $e) {
                Log::error('Lỗi gửi email LDAP', [
                    'ma_tai_khoan' => $ldapAccount->MaTaiKhoan ?? 'Không xác định',
                    'error' => $e->getMessage()
                ]);
                $errors[] = $e->getMessage();
            }
        }

        return response()->json([
            'sent' => $sentAccounts,
            'errors' => $errors
        ]);
    }
    private function taoEmailCUSC($sinhVien)
    {
        // Loại bỏ dấu và chuyển sang chữ thường
        $hoTenKhongDau = $this->loaiBoKyTuDacBiet($sinhVien->HoTen);

        // Tạo email duy nhất
        $baseEmail = strtolower(
            $sinhVien->MaSV .
            substr(preg_replace('/\s+/', '', $hoTenKhongDau), 0, 8)
        );

        // Kiểm tra và thêm hậu tố nếu email đã tồn tại
        $email = $baseEmail;
        $suffix = 1;
        while (LdapAccount::where('username', $email)->exists()) {
            $email = $baseEmail . $suffix;
            $suffix++;
        }

        return $email;
    }
    private function loaiBoKyTuDacBiet($text)
    {
        $text = preg_replace('/[áàảãạăắằẳẵặâấầẩẫậ]/u', 'a', $text);
        $text = preg_replace('/[éèẻẽẹêếềểễệ]/u', 'e', $text);
        $text = preg_replace('/[íìỉĩị]/u', 'i', $text);
        $text = preg_replace('/[óòỏõọôốồổỗộơớờởỡợ]/u', 'o', $text);
        $text = preg_replace('/[úùủũụưứừửữự]/u', 'u', $text);
        $text = preg_replace('/[ýỳỷỹỵ]/u', 'y', $text);
        $text = preg_replace('/[đ]/u', 'd', $text);

        return preg_replace('/[^a-zA-Z0-9]/', '', $text);
    }
    public function editLdapAccount($id)
    {
        $ldapAccount = LdapAccount::findOrFail($id);
        return view('quanly_nhansu.sinhvien.account.edit_account', compact('ldapAccount'));
    }
    public function updateLdapAccount(Request $request, $id)
    {
        $ldapAccount = LdapAccount::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('ldap_accounts', 'username')->ignore($ldapAccount->id)
            ],
            'new_password' => [
                'nullable',
                'confirmed'
            ],
            'email' => 'required|email|max:100',
            'is_active' => 'boolean'
        ], [
            'username.required' => 'Vui lòng nhập tên tài khoản.',
            'username.unique' => 'Tên tài khoản đã tồn tại.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $ldapAccount->update([
                'username' => $request->username,
                'email' => $request->email,
                'is_active' => $request->has('is_active')
            ]);

            if ($request->filled('new_password')) {
                $updateData['initial_password'] = Hash::make($request->new_password);
            }
            
            DB::commit();
            return redirect()->route('ldap.account.list')
                ->with('success', 'Cập nhật tài khoản thành công');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    public function destroyLdapAccount($id)
    {
        DB::beginTransaction();
        try {
            $ldapAccount = LdapAccount::findOrFail($id);

            // Kiểm tra nếu tài khoản đã được sử dụng
            $user = $ldapAccount->getUser();
            if ($user) {
                return back()->with('error', 'Không thể xóa tài khoản đã được gán cho người dùng');
            }

            $ldapAccount->delete();

            DB::commit();
            return redirect()->route('ldap.account.list')
                ->with('success', 'Xóa tài khoản thành công');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    // app/Http/Controllers/NhanSu/SinhVienController.php
    public function toggleLdapAccountStatus($id)
    {
        DB::beginTransaction();
        try {
            $ldapAccount = LdapAccount::findOrFail($id);

            // Đảo ngược trạng thái hiện tại
            $newStatus = !$ldapAccount->is_active;

            $ldapAccount->update([
                'is_active' => $newStatus
            ]);

            DB::commit();

            // Trả về thông báo phù hợp
            $message = $newStatus
                ? 'Kích hoạt tài khoản thành công'
                : 'Vô hiệu hóa tài khoản thành công';

            return response()->json([
                'success' => true,
                'is_active' => $newStatus,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}