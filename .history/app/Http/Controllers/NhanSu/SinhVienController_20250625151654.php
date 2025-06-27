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

class SinhVienController extends Controller
{
    public function index()
    {
        $sinhViens = sinhvien::with(['hosotuyensinh', 'danhSachLop'])->get();
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
    // Phương thức đồng bộ tài khoản LDAP
    public function dongBoTaiKhoanLDAP()
    {
        // Cấu hình LDAP
        $domain = 'CUSC';
        $ldapconfig = [
            'host' => '10.0.0.2',
            'port' => 389,
            'basedn' => 'dc=cusc,dc=ctu,dc=vn',
        ];

        $ds = null;

        try {
            // Ghi log thử kết nối
            Log::info('Attempting LDAP connection', ['host' => $ldapconfig['host'], 'port' => $ldapconfig['port']]);

            // Thực hiện kết nối LDAP
            $ds = ldap_connect($ldapconfig['host'], $ldapconfig['port']);
            if (!$ds) {
                throw new \Exception('Không thể kết nối đến máy chủ LDAP');
            }

            // Cài đặt các tùy chọn LDAP
            ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
            ldap_set_option($ds, LDAP_OPT_NETWORK_TIMEOUT, 10);

            // Thử bind với tài khoản quản trị
            $bind_string = $domain . '\\Administrator';
            $password = 'Huynhphan@0322';

            $bind = ldap_bind($ds, $bind_string, $password);
            if (!$bind) {
                $error = ldap_error($ds);
                $errno = ldap_errno($ds);
                throw new \Exception("Kết nối LDAP thất bại: $error (Mã lỗi: $errno)");
            }

            // Lấy sinh viên chưa có tài khoản LDAP
            // $sinhViens = SinhVien::whereDoesntHave('ldapAccount')->where('EmailCUSC', '')->get();
            $sinhViens = SinhVien::whereNull('EmailCUSC')
                // ->orWhereNull('password_CUSC')
                ->get();
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

                    // Chuẩn bị thông tin LDAP
                    $sanitizedEmail = preg_replace('/[^a-zA-Z0-9]/', '', $email);

                    // Tạo DN cho user mới (chú ý OU)
                    $dn = "CN={$sanitizedEmail},CN=Users,{$ldapconfig['basedn']}";


                    // Thông tin entry LDAP với các thuộc tính bắt buộc
                    $entry = [
                        'objectClass' => ['top', 'person', 'organizationalPerson', 'user'],
                        'cn' => [$sanitizedEmail],
                        'sAMAccountName' => [$sanitizedEmail],
                        'userPrincipalName' => [$fullEmail],
                        'displayName' => [$sinhVien->HoTen],
                        'mail' => [$fullEmail],

                        // Thêm các thuộc tính để tránh vi phạm ràng buộc
                        'givenName' => [explode(' ', $sinhVien->HoTen)[0]],
                        'sn' => [array_slice(explode(' ', $sinhVien->HoTen), -1)[0]],
                        'initials' => [substr($sinhVien->HoTen, 0, 1)]
                    ];

                    // Thêm user vào LDAP với xử lý ngoại lệ chi tiết
                    $addResult = ldap_add($ds, $dn, $entry);
                    if (!$addResult) {
                        $error = ldap_error($ds);
                        $errno = ldap_errno($ds);

                        // Log chi tiết lỗi
                        Log::error('Chi tiết lỗi LDAP khi thêm user', [
                            'error_message' => $error,
                            'error_number' => $errno,
                            'dn' => $dn,
                            'entry' => $entry
                        ]);

                        // Xử lý các mã lỗi cụ thể
                        switch ($errno) {
                            case 68:  // Entry Already Exists
                                throw new \Exception("Tài khoản đã tồn tại trong hệ thống LDAP");
                            case 49:  // Invalid Credentials
                                throw new \Exception("Lỗi xác thực: Không đủ quyền tạo tài khoản");
                            case 53:  // Server Down
                                throw new \Exception("Máy chủ LDAP không khả dụng");
                            default:
                                throw new \Exception("Lỗi không xác định khi thêm user LDAP: $error (Mã lỗi: $errno)");
                        }
                    }

                    // Đặt mật khẩu
                    // $plainPassword = Str::random(8);
                    // $encodedPassword = iconv('UTF-8', 'UTF-16LE', '"' . $plainPassword . '"');

                    // // Đặt mật khẩu
                    // if (!ldap_mod_replace($ds, $dn, ['unicodePwd' => $encodedPassword])) {
                    //     throw new \Exception("Lỗi đặt mật khẩu: " . ldap_error($ds));
                    // }

                    // // Kích hoạt tài khoản
                    // if (!ldap_mod_replace($ds, $dn, ['userAccountControl' => 512])) {
                    //     throw new \Exception("Lỗi kích hoạt tài khoản: " . ldap_error($ds));
                    // }

                    // 1. Disable tài khoản tạm thời (bắt buộc để đặt mật khẩu mới trong một số cấu hình AD)
                    // if (!ldap_mod_replace($ds, $dn, ['userAccountControl' => 544])) {
                    //     throw new \Exception("Không thể đặt trạng thái Disable trước khi đặt mật khẩu: " . ldap_error($ds));
                    // }

                    // // 2. Đặt mật khẩu (phải qua LDAPS)
                    // $plainPassword = Str::random(8);
                    // $encodedPassword = iconv('UTF-8', 'UTF-16LE', '"' . $password . '"');

                    // if (!ldap_mod_replace($ds, $dn, ['unicodePwd' => $encodedPassword])) {
                    //     throw new \Exception("Lỗi đặt mật khẩu: " . ldap_error($ds));
                    // }

                    // // 3. Kích hoạt lại tài khoản (Normal Account, Enabled)
                    // if (!ldap_mod_replace($ds, $dn, ['userAccountControl' => 512])) {
                    //     throw new \Exception("Lỗi kích hoạt tài khoản: " . ldap_error($ds));
                    // }


                    // Thêm vào group Students
                    $studentGroupDN = "CN=Student,CN=Users,{$ldapconfig['basedn']}";
                    $addToGroupResult = ldap_mod_add($ds, $studentGroupDN, ['member' => $dn]);

                    if (!$addToGroupResult) {
                        $error = ldap_error($ds);
                        $errno = ldap_errno($ds);
                        Log::warning("Không thể thêm user vào group Student: $error (Mã lỗi: $errno)");
                    }

                    // Tạo tài khoản LDAP trong CSDL
                    $ldapAccount = LdapAccount::create([
                        'MaTaiKhoan' => $sinhVien->MaSV,
                        'username' => $sanitizedEmail,
                        'email' => $fullEmail,
                        'full_name' => $sinhVien->HoTen,
                        'initial_password' => $password,
                        'role' => 'student',
                        'is_sent' => false,
                        'is_active' => true
                    ]);

                    // Cập nhật thông tin sinh viên
                    $sinhVien->update([
                        'EmailCUSC' => $fullEmail
                    ]);

                    // Commit giao dịch
                    DB::commit();

                    $successCount++;
                    Log::info('Đồng bộ LDAP thành công', [
                        'MaTaiKhoan' => $sinhVien->MaSV,
                        'Email' => $fullEmail
                    ]);

                } catch (\Exception $userException) {
                    // Rollback giao dịch
                    DB::rollBack();

                    $errorCount++;
                    $errorDetails[] = [
                        'MaTaiKhoan' => $sinhVien->MaSV,
                        'ho_ten' => $sinhVien->HoTen,
                        'error_message' => $userException->getMessage()
                    ];

                    Log::error('Lỗi đồng bộ LDAP', [
                        'ma_sv' => $sinhVien->MaSV,
                        'error' => $userException->getMessage()
                    ]);

                    continue;
                }
            }

            // Đóng kết nối LDAP
            ldap_close($ds);

            // Thông báo kết quả
            $message = "Đồng bộ hoàn tất. Thành công: $successCount, Lỗi: $errorCount";
            Log::info($message, ['error_details' => $errorDetails]);

            return redirect()->route('student.list')
                ->with('success', $message)
                ->with('error_details', $errorDetails);

        } catch (\Exception $e) {
            // Đóng kết nối LDAP nếu còn mở
            if ($ds) {
                ldap_close($ds);
            }

            Log::error('Lỗi đồng bộ LDAP toàn bộ', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('student.list')
                ->with('error', 'Lỗi đồng bộ: ' . $e->getMessage());
        }
    }
    // Phương thức tạo mật khẩu LDAP
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
    // Phương thức xuất danh sách tài khoản mới
    public function xuatDanhSachTaiKhoanMoi()
    {
        $ldapAccounts = LdapAccount::orderBy('created_at', 'desc')
            ->where('role', 'student')
            ->get();

        return view('quanly_nhansu.sinhvien.list_account', compact('ldapAccounts'));
    }
    // Phương thức gửi thông tin tài khoản qua email
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
    // Giữ nguyên các phương thức tạo email và mật khẩu như trước
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
    public function kiemTraDongBoLDAP()
    {
        // Cấu hình LDAP
        $domain = 'CUSC';
        $ldapconfig = [
            'host' => '10.0.0.2',
            'port' => 389,
            'basedn' => 'dc=cusc,dc=ctu,dc=vn',
        ];

        try {
            // Ghi log thử kết nối
            Log::info('Attempting LDAP connection', ['host' => $ldapconfig['host'], 'port' => $ldapconfig['port']]);

            // Thực hiện kết nối LDAP
            $ds = ldap_connect($ldapconfig['host'], $ldapconfig['port']);
            if (!$ds) {
                throw new \Exception('Không thể kết nối đến máy chủ LDAP');
            }

            // Cài đặt các tùy chọn LDAP
            ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
            ldap_set_option($ds, LDAP_OPT_NETWORK_TIMEOUT, 10);

            $password = session('password');
            $password = session('password');

            // Thử bind với tài khoản quản trị
            $bind_string = $domain . '\\admin.khuong';
            $password = 'P@ssW@rd2025!';

            $bind = ldap_bind($ds, $bind_string, $password);
            if (!$bind) {
                $error = ldap_error($ds);
                $errno = ldap_errno($ds);
                throw new \Exception("Kết nối LDAP thất bại: $error (Mã lỗi: $errno)");
            }

            // Đóng kết nối
            ldap_close($ds);

            // Kiểm tra số lượng sinh viên chưa có tài khoản
            $sinhViensCount = SinhVien::whereNull('EmailCUSC')
                // ->orWhereNull('password_CUSC')
                ->count();
            // $sinhViensCount = SinhVien::whereDoesntHave('ldapAccount')->where('EmailCUSC', '')->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Kết nối đến máy chủ thành công',
                'sinh_viens_chua_co_tai_khoan' => $sinhViensCount
            ]);

        } catch (\Exception $e) {
            // Ghi log lỗi chi tiết
            Log::error('LDAP Connection Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi kiểm tra LDAP',
                'error' => $e->getMessage()
            ]);
        }
    }
}