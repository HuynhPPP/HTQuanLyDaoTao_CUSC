<?php

namespace App\Http\Controllers\NhanSu;
use App\Http\Controllers\Controller;

use App\Models\sinhvien;
use App\Models\hoso;
use App\Models\danhsachsv;
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
        $dn = null;

        try {
            // Ghi log thử kết nối
            Log::info('Bắt đầu đồng bộ tài khoản LDAP', ['host' => $ldapconfig['host'], 'port' => $ldapconfig['port']]);

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

            // Lấy sinh viên chưa có tài khoản
            $sinhViens = SinhVien::whereNull('EmailCUSC')
                ->orWhereNull('password_CUSC')
                ->limit(10) // Giới hạn số lượng để kiểm tra
                ->get();

            Log::info('Số sinh viên cần đồng bộ: ' . $sinhViens->count());

            $successCount = 0;
            $errorCount = 0;
            $errorDetails = [];

            foreach ($sinhViens as $sinhVien) {
                try {
                    // Tạo email và mật khẩu
                    $email = $this->taoEmailCUSC($sinhVien);
                    $password = $this->taoMatKhauManh($sinhVien);
                    $fullEmail = $email . '@cusc.ctu.vn';

                    Log::info('Xử lý sinh viên: ' . $sinhVien->MaSV, [
                        'email' => $fullEmail,
                        'ho_ten' => $sinhVien->HoTen
                    ]);

                    // Kiểm tra tính hợp lệ của email và mật khẩu
                    if (empty($email) || empty($password)) {
                        throw new \Exception('Email hoặc mật khẩu không hợp lệ');
                    }

                    // Chuẩn bị thông tin user LDAP
                    $entry = [
                        'cn' => [$sinhVien->HoTen],
                        'sAMAccountName' => [$email],
                        'userPrincipalName' => [$fullEmail],
                        'mail' => [$fullEmail],
                        'displayName' => [$sinhVien->HoTen],
                        'objectClass' => ['top', 'person', 'organizationalPerson', 'user']
                    ];

                    // Tạo DN cho user mới
                    $dn = "CN={$email},CN=Users,{$ldapconfig['basedn']}";

                    // Thêm user vào LDAP
                    $addResult = ldap_add($ds, $dn, $entry);
                    if (!$addResult) {
                        $error = ldap_error($ds);
                        $errno = ldap_errno($ds);
                        throw new \Exception("Không thể tạo user LDAP: $error (Mã lỗi: $errno)");
                    }

                    // Đặt mật khẩu
                    $passwordModify = ldap_mod_replace($ds, $dn, ['unicodePwd' => iconv('UTF-8', 'UTF-16LE', '"' . $password . '"')]);
                    if (!$passwordModify) {
                        $error = ldap_error($ds);
                        $errno = ldap_errno($ds);
                        throw new \Exception("Không thể đặt mật khẩu: $error (Mã lỗi: $errno)");
                    }

                    // Thêm vào group Student
                    $studentGroupDN = "CN=Student,CN=Users,{$ldapconfig['basedn']}";
                    $addToGroupResult = ldap_mod_add($ds, $studentGroupDN, ['member' => $dn]);
                    if (!$addToGroupResult) {
                        $error = ldap_error($ds);
                        $errno = ldap_errno($ds);
                        Log::warning("Không thể thêm user vào group Student: $error (Mã lỗi: $errno)");
                    }

                    // Cập nhật CSDL
                    $updateResult = $sinhVien->update([
                        'EmailCUSC' => $fullEmail,
                        'password_CUSC' => Hash::make($password)
                    ]);

                    if (!$updateResult) {
                        throw new \Exception('Không thể cập nhật thông tin sinh viên');
                    }

                    $successCount++;
                    Log::info('Đồng bộ thành công cho sinh viên: ' . $sinhVien->MaSV);

                } catch (\Exception $userException) {
                    $errorCount++;
                    $errorDetails[] = [
                        'ma_sv' => $sinhVien->MaSV,
                        'ho_ten' => $sinhVien->HoTen,
                        'error_message' => $userException->getMessage()
                    ];
                    Log::error('Lỗi đồng bộ cho sinh viên ' . $sinhVien->MaSV, [
                        'error' => $userException->getMessage(),
                        'trace' => $userException->getTraceAsString()
                    ]);
                    continue;
                }
            }

            // Đóng kết nối LDAP
            if ($ds) {
                ldap_close($ds);
            }

            // Thông báo kết quả
            $message = "Đồng bộ hoàn tất. Thành công: $successCount, Lỗi: $errorCount";
            Log::info($message, ['error_details' => $errorDetails]);

            // Nếu có lỗi, trả về chi tiết lỗi
            if ($errorCount > 0) {
                return redirect()->route('student.list')
                    ->with('error', $message)
                    ->with('error_details', $errorDetails);
            }

            return redirect()->route('student.list')
                ->with('success', $message);

        } catch (\Exception $e) {
            // Đóng kết nối LDAP nếu còn mở
            if ($ds) {
                ldap_close($ds);
            }

            // Ghi log lỗi chi tiết
            Log::error('Lỗi đồng bộ LDAP toàn bộ', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('student.list')
                ->with('error', 'Lỗi đồng bộ: ' . $e->getMessage());
        }
    }

    // Giữ nguyên các phương thức tạo email và mật khẩu như trước
    private function taoEmailCUSC($sinhVien)
    {
        // Loại bỏ dấu và chuyển sang chữ thường
        $hoTenKhongDau = $this->loaiBoKyTuDacBiet($sinhVien->HoTen);

        // Tạo email từ mã sinh viên và tên
        $email = strtolower(
            $sinhVien->MaSV .
            substr(preg_replace('/\s+/', '', $hoTenKhongDau), 0, 5)
        );

        return $email;
    }

    private function taoMatKhauManh($sinhVien)
    {
        // Tạo mật khẩu phức tạp
        $kyTuDacBiet = ['!', '@', '#', '$', '%', '^', '&', '*'];
        $matKhau =
            Str::upper(substr($this->loaiBoKyTuDacBiet($sinhVien->HoTen), 0, 2)) .
            $sinhVien->MaSV .
            $kyTuDacBiet[array_rand($kyTuDacBiet)] .
            Str::random(4);

        return $matKhau;
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

            return response()->json([
                'status' => 'success',
                'message' => 'Kết nối LDAP thành công',
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
