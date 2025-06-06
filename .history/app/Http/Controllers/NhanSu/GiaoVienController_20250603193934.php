<?php

namespace App\Http\Controllers\NhanSu;

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
use Illuminate\Support\Facades\DB;
use App\Mail\LdapAccountInfoMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class GiaoVienController extends Controller
{
    public function index()
    {
        $giaoviens = giaovien::with([
            'hocvi',
            'chucvu',
            'donvi',
            'bangcapcanbo'
        ])->paginate(10);

        return view('quanly_nhansu.giaovien.index', compact('giaoviens'));
    }
    public function create()
    {
        $hocvis = hocvi::all();
        $bangcaps = bangcapcanbo::all();
        $chucvus = chucvu::all();
        $donvis = donvi::all();

        return view('quanly_nhansu.giaovien.create', compact(
            'hocvis',
            'bangcaps',
            'chucvus',
            'donvis'
        ));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'MaGV' => 'required|unique:giaovien,MaGV',
            'HoTenGV' => 'required',
            'Email' => 'required|email|unique:giaovien,Email',
            'GioiTinh' => 'required',
            'LoaiGV' => 'required|in:CoHuu,MoiGiang',
        ], [
            'MaGV.required' => 'Vui lòng nhập mã giáo viên',
            'MaGV.unique' => 'Mã giáo viên đã tồn tại',
            'HoTenGV.required' => 'Vui lòng nhập họ tên giáo viên',
            'Email.required' => 'Vui lòng nhập email',
            'Email.email' => 'Email không đúng định dạng',
            'Email.unique' => 'Email đã tồn tại',
            'GioiTinh.required' => 'Vui lòng chọn giới tính',
            'LoaiGV.required' => 'Vui lòng chọn loại giáo viên',
        ]);

        try {
            // Xử lý các khóa ngoại
            $this->handleForeignKeys($request);

            // Tạo giáo viên mới
            giaovien::create($request->all());

            return redirect()->route('giaovien.index')
                ->with('success', 'Thêm giáo viên thành công');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    public function show($maGV)
    {
        $giaovien = giaovien::with([
            'hocvi',
            'chucvu',
            'donvi',
            'bangcapcanbo'
        ])->findOrFail($maGV);

        return view('quanly_nhansu.giaovien.show', compact('giaovien'));
    }
    public function edit($maGV)
    {
        $giaovien = giaovien::findOrFail($maGV);

        $hocvis = hocvi::all();
        $bangcaps = bangcapcanbo::all();
        $chucvus = chucvu::all();
        $donvis = donvi::all();

        return view('quanly_nhansu.giaovien.edit', compact(
            'giaovien',
            'hocvis',
            'bangcaps',
            'chucvus',
            'donvis'
        ));
    }
    public function update(Request $request, $maGV)
    {
        $giaovien = giaovien::findOrFail($maGV);

        $validated = $request->validate([
            'HoTenGV' => 'required',
            'Email' => 'required|email|unique:giaovien,Email,' . $maGV . ',MaGV',
            'GioiTinh' => 'required',
            'LoaiGV' => 'required|in:CoHuu,MoiGiang',
        ], [
            'HoTenGV.required' => 'Vui lòng nhập họ tên giáo viên',
            'Email.required' => 'Vui lòng nhập email',
            'Email.email' => 'Email không đúng định dạng',
            'Email.unique' => 'Email đã tồn tại',
            'GioiTinh.required' => 'Vui lòng chọn giới tính',
            'LoaiGV.required' => 'Vui lòng chọn loại giáo viên',
        ]);

        try {
            // Xử lý các khóa ngoại
            $this->handleForeignKeys($request);

            // Cập nhật thông tin giáo viên
            $giaovien->update($request->all());

            return redirect()->route('giaovien.index')
                ->with('success', 'Cập nhật thông tin giáo viên thành công');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    public function destroy($maGV)
    {
        try {
            $giaovien = giaovien::findOrFail($maGV);
            $giaovien->delete();

            return redirect()->route('giaovien.index')
                ->with('success', 'Xóa giáo viên thành công');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ], [
            'file.required' => 'Vui lòng chọn file để import',
            'file.mimes' => 'File phải có định dạng Excel (.xlsx, .xls, .csv)'
        ]);

        try {
            // Thực hiện import
            Excel::import(new GiaoVienImport, $request->file('file'));

            // Lấy số lượng import thành công và lỗi
            $successCount = session('import_success_count', 0);
            $errors = session('import_errors', []);

            // Xử lý thông báo
            if ($successCount > 0) {
                $message = "Import thành công $successCount giáo viên.";
                if (!empty($errors)) {
                    $message .= " Có " . count($errors) . " dòng bị lỗi.";
                }
                return redirect()->route('giaovien.index')
                    ->with('success', $message);
            } else {
                return back()
                    ->with('warning', 'Không có dữ liệu nào được import.');
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            return back()
                ->with('warning', 'Lỗi import: ' . $failures[0]->errors()[0]);
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    private function handleForeignKeys($request)
    {
        // Xử lý học vị
        if ($request->filled('MaHV') && !hocvi::where('MaHV', $request->MaHV)->exists()) {
            hocvi::create([
                'MaHV' => $request->MaHV,
                'TenHocVi' => $request->filled('TenHocVi')
                    ? $request->TenHocVi
                    : 'Học vị ' . $request->MaHV,
            ]);
        }

        // Xử lý chức vụ
        if ($request->filled('TenChucVu') && !chucvu::where('TenChucVu', $request->TenChucVu)->exists()) {
            chucvu::create([
                'TenChucVu' => $request->TenChucVu,
            ]);
        }

        // Xử lý đơn vị
        if ($request->filled('MaDV') && !donvi::where('MaDV', $request->MaDV)->exists()) {
            donvi::create([
                'MaDV' => $request->MaDV,
                'TenDVHienTai' => $request->filled('TenDVHienTai')
                    ? $request->TenDVHienTai
                    : 'Đơn vị ' . $request->MaDV,
            ]);
        }

        // Xử lý bằng cấp
        if ($request->filled('MaBang') && !bangcapcanbo::where('MaBang', $request->MaBang)->exists()) {
            bangcapcanbo::create([
                'MaBang' => $request->MaBang,
                'TenBang' => $request->filled('TenBang')
                    ? $request->TenBang
                    : 'Bằng cấp ' . $request->MaBang,
            ]);
        }
    }
    // Phương thức đồng bộ tài khoản LDAP
    public function dongBoTaiKhoanGVLDAP()
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

            // Lấy giáo viên chưa có tài khoản LDAP
            $giaoViens = giaovien::whereNull('EmailCUSC')
                // ->orWhereNull('password_CUSC')
                ->get();
            $successCount = 0;
            $errorCount = 0;
            $errorDetails = [];

            foreach ($giaoViens as $giaoVien) {
                DB::beginTransaction();
                try {
                    // Tạo email và mật khẩu
                    $email = $this->taoEmailCUSC($giaoVien);
                    $password = $this->taoMatKhauManh();
                    $fullEmail = $email . '@cusc.ctu.vn';

                    // Chuẩn bị thông tin LDAP
                    $sanitizedEmail = preg_replace('/[^a-zA-Z0-9]/', '', $email);

                    // Tạo DN cho user mới (chú ý OU)
                    $dn = "CN={$sanitizedEmail},CN=Users,{$ldapconfig['basedn']}";

                    // Làm sạch tên để tránh các vấn đề với ký tự Unicode
                    $sanitizedName = $this->sanitizeAndTransformName($giaoVien->HoTenGV);

                    // Thông tin entry LDAP với các thuộc tính bắt buộc
                    $entry = [
                        'objectClass' => ['top', 'person', 'organizationalPerson', 'user'],
                        'cn' => [$sanitizedEmail],
                        'sAMAccountName' => [$sanitizedEmail],
                        'userPrincipalName' => [$fullEmail],
                        'displayName' => [$sanitizedName],
                        'mail' => [$fullEmail],

                        // Xử lý tên riêng và họ
                        'givenName' => [explode(' ', $sanitizedName)[0]],
                        'sn' => [array_slice(explode(' ', $sanitizedName), -1)[0]],
                        'initials' => [substr($sanitizedName, 0, 1)]
                    ];

                    // Trước khi gọi ldap_add, thêm log chi tiết
                    Log::info('LDAP Entry Details', [
                        'dn' => $dn,
                        'entry' => $entry
                    ]);

                    // Thêm user vào LDAP với xử lý ngoại lệ chi tiết
                    $addResult = ldap_add($ds, $dn, $entry);
                    if (!$addResult) {
                        $error = ldap_error($ds);
                        $errno = ldap_errno($ds);

                        Log::error('Chi tiết lỗi LDAP khi thêm user', [
                            'error_message' => $error,
                            'error_number' => $errno,
                            'dn' => $dn,
                            'entry' => $entry,
                            'ldap_details' => [
                                'host' => $ldapconfig['host'],
                                'port' => $ldapconfig['port'],
                                'basedn' => $ldapconfig['basedn']
                            ]
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

                    // Thêm vào group Students
                    $studentGroupDN = "CN=Teacher,CN=Users,{$ldapconfig['basedn']}";
                    $addToGroupResult = ldap_mod_add($ds, $studentGroupDN, ['member' => $dn]);

                    if (!$addToGroupResult) {
                        $error = ldap_error($ds);
                        $errno = ldap_errno($ds);
                        Log::warning("Không thể thêm user vào group Student: $error (Mã lỗi: $errno)");
                    }

                    // Tạo tài khoản LDAP trong CSDL
                    $ldapAccount = LdapAccount::create([
                        'MaTaiKhoan' => $giaoVien->MaGV,
                        'username' => $sanitizedEmail,
                        'email' => $fullEmail,
                        'full_name' => $giaoVien->HoTenGV,
                        'initial_password' => $password,
                        'role' => 'teacher',
                        'is_sent' => false,
                        'is_active' => true
                    ]);

                    // Cập nhật thông tin giáo viên
                    $giaoVien->update([
                        'EmailCUSC' => $fullEmail
                    ]);

                    // Commit giao dịch
                    DB::commit();

                    $successCount++;
                    Log::info('Đồng bộ LDAP thành công', [
                        'MaTaiKhoan' => $giaoVien->MaGV,
                        'Email' => $fullEmail
                    ]);

                    // Log thông tin đã làm sạch tên
                    Log::info('Sanitized Name Process', [
                        'original_name' => $giaoVien->HoTenGV,
                        'sanitized_name' => $sanitizedName,
                        'email' => $sanitizedEmail,
                        'full_email' => $fullEmail
                    ]);

                } catch (\Exception $userException) {
                    // Rollback giao dịch
                    DB::rollBack();

                    $errorCount++;
                    $errorDetails[] = [
                        'MaTaiKhoan' => $giaoVien->MaGV,
                        'ho_ten' => $giaoVien->HoTenGV,
                        'error_message' => $userException->getMessage()
                    ];

                    Log::error('Lỗi đồng bộ LDAP', [
                        'MaTaiKhoan' => $giaoVien->MaGV,
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

            return redirect()->route('giaovien.index')
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

            return redirect()->route('giaovien.index')
                ->with('error', 'Lỗi đồng bộ: ' . $e->getMessage());
        }
    }
    // Phương thức tạo mật khẩu LDAP
    private function taoMatKhauManh($length = 12)
    {
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $digits = '0123456789';
        $special = '!@#$%^&*()_+-=[]{}|;:,.<>?';

        // Đảm bảo có ít nhất 1 ký tự từ mỗi nhóm
        $password = [
            $upper[random_int(0, strlen($upper) - 1)],
            $lower[random_int(0, strlen($lower) - 1)],
            $digits[random_int(0, strlen($digits) - 1)],
            $special[random_int(0, strlen($special) - 1)],
        ];

        // Thêm các ký tự ngẫu nhiên khác
        $all = $upper . $lower . $digits . $special;
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
            ->where('role', 'teacher')
            ->get();

        return view('quanly_nhansu.giaovien.list_account', compact('ldapAccounts'));
    }
    // Phương thức gửi thông tin tài khoản qua email
    public function guiThongTinTaiKhoan($id)
    {
        $ldapAccount = LdapAccount::findOrFail($id);
        $sinhVien = $ldapAccount->sinhVien;

        try {
            // Gửi email chứa thông tin đăng nhập
            Mail::to($sinhVien->Email)->send(new LdapAccountInfoMail($ldapAccount));

            // Đánh dấu tài khoản đã được gửi
            $ldapAccount->update(['is_sent' => true]);

            return redirect()->back()->with('success', 'Đã gửi thông tin tài khoản cho sinh viên');
        } catch (\Exception $e) {
            Log::error('Lỗi gửi email LDAP', [
                'ma_sv' => $sinhVien->MaSV,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Không thể gửi email: ' . $e->getMessage());
        }
    }
    // Giữ nguyên các phương thức tạo email và mật khẩu như trước
    private function taoEmailCUSC($giaoVien)
    {
        // Loại bỏ dấu và chuyển sang chữ thường
        $hoTenKhongDau = $this->loaiBoKyTuDacBiet($giaoVien->HoTen);

        // Tạo email duy nhất
        $baseEmail = strtolower(
            $giaoVien->MaSV .
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
    public function kiemTraDongBoGVLDAP()
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
            $giaoViensCount = giaovien::whereNull('EmailCUSC')
                // ->orWhereNull('password_CUSC')
                ->count();
            // $sinhViensCount = SinhVien::whereDoesntHave('ldapAccount')->where('EmailCUSC', '')->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Kết nối LDAP thành công',
                'so_luong_giao_vien_chua_co_tai_khoan' => $giaoViensCount
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
    private function sanitizeAndTransformName($name)
    {
        // Loại bỏ các ký tự đặc biệt và chuẩn hóa
        $name = preg_replace('/[^\p{L}\s]/u', '', $name);
        $name = trim($name);

        // Chuyển đổi ký tự có dấu sang không dấu
        $name = $this->convertViToEn($name);

        return $name;
    }
    private function convertViToEn($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        return $str;
    }
}
