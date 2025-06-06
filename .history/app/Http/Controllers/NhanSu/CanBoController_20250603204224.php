<?php

namespace App\Http\Controllers\NhanSu;
use App\Http\Controllers\Controller;
use App\Models\canbo;
use App\Models\hocvi;
use App\Models\bangcapcanbo;
use App\Models\chucvu;
use App\Models\donvi;
use App\Models\TapHuan;
use App\Models\LdapAccount;
use App\Imports\CanBoImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Mail\LdapAccountInfoMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CanBoController extends Controller
{
    public function index()
    {
        $canbos = CanBo::with([
            'hocvi',
            'chucvu',
            'donvi',
            'bangcapcanbo',
            'taphuan'
        ])->get();

        return view('quanly_nhansu.canbo.index', compact('canbos'));
    }
    public function create()
    {
        $hocvis = HocVi::all();
        $bangcaps = BangCapCanBo::all();
        $chucvus = ChucVu::all();
        $donvis = DonVi::all();
        $taphuans = TapHuan::all();
        return view('quanly_nhansu.canbo.create', compact('hocvis', 'bangcaps', 'chucvus', 'donvis', 'taphuans'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'MaCB' => 'required|unique:canbo,MaCB',
            'HoTenCB' => 'required',
            'GioiTinh' => 'required',
            'Email' => 'required|email|unique:canbo,Email',
            'Sdt' => 'required|regex:/^[0-9]{10,11}$/',
        ], [
            'MaCB.required' => 'Vui lòng nhập mã cán bộ',
            'MaCB.unique' => 'Mã cán bộ đã tồn tại trong hệ thống',
            'HoTenCB.required' => 'Vui lòng nhập họ tên cán bộ',
            'GioiTinh.required' => 'Vui lòng chọn giới tính',
            'Email.required' => 'Vui lòng nhập email',
            'Email.email' => 'Email không đúng định dạng',
            'Email.unique' => 'Email đã tồn tại trong hệ thống',
            'Sdt.required' => 'Vui lòng nhập số điện thoại',
            'Sdt.regex' => 'Số điện thoại phải có 10-11 chữ số',
        ]);

        try {
            // Xử lý học vị nếu chưa tồn tại
            if ($request->filled('MaHV') && !HocVi::where('MaHV', $request->MaHV)->exists()) {
                HocVi::create([
                    'MaHV' => $request->MaHV,
                    'TenHocVi' => $request->filled('TenHocVi') ? $request->TenHocVi : 'Học vị ' . $request->MaHV,
                ]);
            }

            // Xử lý chức vụ nếu chưa tồn tại
            if ($request->filled('TenChucVu') && !ChucVu::where('TenChucVu', $request->TenChucVu)->exists()) {
                ChucVu::create([
                    'TenChucVu' => $request->TenChucVu,
                ]);
            }

            // Xử lý đơn vị nếu chưa tồn tại
            if ($request->filled('MaDV') && !DonVi::where('MaDV', $request->MaDV)->exists()) {
                DonVi::create([
                    'MaDV' => $request->MaDV,
                    'TenDVHienTai' => $request->filled('TenDVHienTai') ? $request->TenDVHienTai : 'Đơn vị ' . $request->MaDV,
                ]);
            }

            // Xử lý bằng cấp nếu chưa tồn tại
            if ($request->filled('MaBang') && !BangCapCanBo::where('MaBang', $request->MaBang)->exists()) {
                BangCapCanBo::create([
                    'MaBang' => $request->MaBang,
                    'TenBang' => $request->filled('TenBang') ? $request->TenBang : 'Bằng cấp ' . $request->MaBang,
                ]);
            }

            // Xử lý tập huấn nếu chưa tồn tại
            if ($request->filled('MaTapHuan') && !TapHuan::where('MaTapHuan', $request->MaTapHuan)->exists()) {
                TapHuan::create([
                    'MaTapHuan' => $request->MaTapHuan,
                    'TenKhoaTapHuan' => $request->filled('TenKhoaTapHuan') ? $request->TenKhoaTapHuan : 'Khóa tập huấn ' . $request->MaTapHuan,
                ]);
            }

            // Tạo cán bộ mới
            CanBo::create($request->all());
            return redirect()->route('staff.index')->with('success', 'Thêm cán bộ thành công');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    public function show($maCB)
    {
        $canbo = CanBo::with([
            'hocvi',
            'chucvu',
            'donvi',
            'bangcapcanbo',
            'taphuan'
        ])->where('MaCB', $maCB)->firstOrFail();
        return view('quanly_nhansu.canbo.show', compact('canbo'));
    }
    public function edit($maCB)
    {
        $canbo = CanBo::where('MaCB', $maCB)->firstOrFail();
        $hocvis = HocVi::all();
        $bangcaps = BangCapCanBo::all();
        $chucvus = ChucVu::all();
        $donvis = DonVi::all();
        $taphuans = TapHuan::all();
        return view('quanly_nhansu.canbo.edit', compact('canbo', 'hocvis', 'bangcaps', 'chucvus', 'donvis', 'taphuans'));
    }
    public function update(Request $request, $maCB)
    {
        $validated = $request->validate([
            'HoTenCB' => 'required',
            'GioiTinh' => 'required',
            'Email' => 'required|email|unique:canbo,Email,' . $maCB . ',MaCB',
            'Sdt' => 'required|regex:/^[0-9]{10,11}$/',
        ], [
            'HoTenCB.required' => 'Vui lòng nhập họ tên cán bộ',
            'GioiTinh.required' => 'Vui lòng chọn giới tính',
            'Email.required' => 'Vui lòng nhập email',
            'Email.email' => 'Email không đúng định dạng',
            'Email.unique' => 'Email đã tồn tại trong hệ thống',
            'Sdt.required' => 'Vui lòng nhập số điện thoại',
            'Sdt.regex' => 'Số điện thoại phải có 10-11 chữ số',
        ]);

        // Xử lý học vị nếu chưa tồn tại
        try {
            // Xử lý học vị nếu chưa tồn tại
            if ($request->filled('MaHV') && !HocVi::where('MaHV', $request->MaHV)->exists()) {
                HocVi::create([
                    'MaHV' => $request->MaHV,
                    'TenHocVi' => $request->filled('TenHocVi') ? $request->TenHocVi : 'Học vị ' . $request->MaHV,
                ]);
            }

            // Xử lý chức vụ nếu chưa tồn tại
            if ($request->filled('TenChucVu') && !ChucVu::where('TenChucVu', $request->TenChucVu)->exists()) {
                ChucVu::create([
                    'TenChucVu' => $request->TenChucVu,
                ]);
            }

            // Xử lý đơn vị nếu chưa tồn tại
            if ($request->filled('MaDV') && !DonVi::where('MaDV', $request->MaDV)->exists()) {
                DonVi::create([
                    'MaDV' => $request->MaDV,
                    'TenDVHienTai' => $request->filled('TenDVHienTai') ? $request->TenDVHienTai : 'Đơn vị ' . $request->MaDV,
                ]);
            }

            // Xử lý bằng cấp nếu chưa tồn tại
            if ($request->filled('MaBang') && !BangCapCanBo::where('MaBang', $request->MaBang)->exists()) {
                BangCapCanBo::create([
                    'MaBang' => $request->MaBang,
                    'TenBang' => $request->filled('TenBang') ? $request->TenBang : 'Bằng cấp ' . $request->MaBang,
                ]);
            }

            // Xử lý tập huấn nếu chưa tồn tại
            if ($request->filled('MaTapHuan') && !TapHuan::where('MaTapHuan', $request->MaTapHuan)->exists()) {
                TapHuan::create([
                    'MaTapHuan' => $request->MaTapHuan,
                    'TenKhoaTapHuan' => $request->filled('TenKhoaTapHuan') ? $request->TenKhoaTapHuan : 'Khóa tập huấn ' . $request->MaTapHuan,
                ]);
            }

            $canbo = CanBo::where('MaCB', $maCB)->firstOrFail();
            $canbo->update($request->all());
            return redirect()->route('staff.index')->with('success', 'Cập nhật thành công');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Đã xảy ra lỗi khi cập nhật: ' . $e->getMessage());
        }
    }
    public function destroy($maCB)
    {
        $canbo = CanBo::where('MaCB', $maCB)->firstOrFail();
        $canbo->delete();
        return redirect()->route('staff.index')->with('success', 'Xóa cán bộ thành công');
    }
    // Thêm phương thức import dữ liệu từ Excel
    public function import(Request $request)
    {
        try {
            $validator = $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv',
            ], [
                'file.required' => 'Vui lòng chọn file để import',
                'file.mimes' => 'File phải có định dạng Excel (.xlsx, .xls, .csv)',
            ]);

            if (!$request->hasFile('file')) {
                return back()->with('error', 'Không tìm thấy file để import');
            }

            Excel::import(new CanBoImport, $request->file('file'));

            if (session()->has('import_errors') && count(session('import_errors')) > 0) {
                return back()->with('warning', 'Đã import ' . session('import_success_count') . ' cán bộ, có một số lỗi:')
                    ->with('import_errors', session('import_errors'));
            }

            return back()->with('success', 'Đã import thành công ' . session('import_success_count') . ' cán bộ');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];

            foreach ($failures as $failure) {
                $errors[] = 'Dòng ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }

            return back()->with('error', 'Lỗi khi import dữ liệu')
                ->with('import_errors', $errors);
        } catch (\Exception $e) {
            return back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
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
            $canBos = canbo::whereNull('EmailCUSC')
                // ->orWhereNull('password_CUSC')
                ->get();
            $successCount = 0;
            $errorCount = 0;
            $errorDetails = [];

            foreach ($canBos as $canBo) {
                DB::beginTransaction();
                try {
                    // Tạo email và mật khẩu
                    $email = $this->taoEmailCUSC($canBo);
                    $password = $this->taoMatKhauManh();
                    $fullEmail = $email . '@cusc.ctu.vn';

                    // Chuẩn bị thông tin LDAP
                    if (empty($email)) {
                        Log::warning('Email rỗng khi tạo tài khoản LDAP', ['MaCB' => $canBo->MaCB]);
                        throw new \Exception("Không tạo được email CUSC cho cán bộ {$canBo->HoTenCB}");
                    }
                    $sanitizedEmail = preg_replace('/[^a-zA-Z0-9]/', '', $email);


                    // Chuẩn hóa họ tên và tách họ - tên
                    $hoTen = trim($canBo->HoTenCB);
                    $tenParts = preg_split('/\s+/', $hoTen);
                    $givenName = $tenParts[0] ?? 'Unknown';
                    $sn = end($tenParts) ?: 'Unknown';
                    $initial = strtoupper(substr($givenName, 0, 1));

                    // Escape CN để dùng trong DN
                    $escapedCN = ldap_escape($hoTen, '', LDAP_ESCAPE_DN);

                    // Tạo Distinguished Name cho tài khoản mới
                    $dn = "CN={$escapedCN},CN=Users,{$ldapconfig['basedn']}";

                    // Tạo thông tin entry đầy đủ và đúng cú pháp
                    $entry = [
                        'objectClass' => ['top', 'person', 'organizationalPerson', 'user'],
                        'cn' => $hoTen,
                        'sAMAccountName' => $sanitizedEmail,
                        'userPrincipalName' => $fullEmail,
                        'displayName' => $hoTen,
                        'mail' => $fullEmail,
                        'givenName' => $givenName,
                        'sn' => $sn,
                        'initials' => $initial,
                        'userAccountControl' => '544', // Enable user account
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

                    // Thêm vào group Students
                    $studentGroupDN = "CN=Staff,CN=Users,{$ldapconfig['basedn']}";
                    $addToGroupResult = ldap_mod_add($ds, $studentGroupDN, ['member' => $dn]);

                    if (!$addToGroupResult) {
                        $error = ldap_error($ds);
                        $errno = ldap_errno($ds);
                        Log::warning("Không thể thêm user vào group Student: $error (Mã lỗi: $errno)");
                    }

                    // Tạo tài khoản LDAP trong CSDL
                    $ldapAccount = LdapAccount::create([
                        'MaTaiKhoan' => $canBo->MaCB,
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
    // Giữ nguyên các phương thức tạo email và mật khẩu như trước
    private function taoEmailCUSC($giaoVien)
    {
        // Bỏ dấu và ký tự đặc biệt, chuyển thành chữ thường
        $hoTenKhongDau = $this->loaiBoKyTuDacBiet($giaoVien->HoTenGV);
        $tenKhongDau = strtolower(preg_replace('/\s+/', '', $hoTenKhongDau));

        // Tạo email với MaGV ở đầu
        $baseEmail = strtolower($giaoVien->MaGV . $tenKhongDau);

        // Cắt ngắn để tránh email quá dài (ví dụ 30 ký tự là hợp lý)
        $baseEmail = substr($baseEmail, 0, 30);

        // Kiểm tra trùng và thêm hậu tố nếu cần
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
        $text = preg_replace('/[áàảãạăắằẳẵặâấầẩẫậ]/iu', 'a', $text);
        $text = preg_replace('/[éèẻẽẹêếềểễệ]/iu', 'e', $text);
        $text = preg_replace('/[íìỉĩị]/iu', 'i', $text);
        $text = preg_replace('/[óòỏõọôốồổỗộơớờởỡợ]/iu', 'o', $text);
        $text = preg_replace('/[úùủũụưứừửữự]/iu', 'u', $text);
        $text = preg_replace('/[ýỳỷỹỵ]/iu', 'y', $text);
        $text = preg_replace('/[đ]/iu', 'd', $text);

        return preg_replace('/[^a-zA-Z0-9\s]/', '', $text);
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
}
