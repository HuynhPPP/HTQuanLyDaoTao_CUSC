<?php

namespace App\Http\Controllers\NhanSu;

use App\Http\Controllers\Controller;
use App\Models\GiangDay;
use App\Models\giaovien;
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
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        return view(
            'quanly_nhansu.giaovien.create'
        );
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'MaGV' => 'required|unique:giaovien,MaGV',
            'HoTenGV' => 'required',
            'Email' => 'required|email|unique:giaovien,Email',
            'GioiTinh' => 'required',
            'LoaiGV' => 'required|in:CoHuu,MoiGiang',
            'ChuyenNganh' => 'required',
            'Sdt' => 'required',
        ], [
            'MaGV.required' => 'Vui lòng nhập mã giáo viên',
            'MaGV.unique' => 'Mã giáo viên đã tồn tại',
            'HoTenGV.required' => 'Vui lòng nhập họ tên giáo viên',
            'Email.required' => 'Vui lòng nhập email',
            'Email.email' => 'Email không đúng định dạng',
            'Email.unique' => 'Email đã tồn tại',
            'GioiTinh.required' => 'Vui lòng chọn giới tính',
            'LoaiGV.required' => 'Vui lòng chọn loại giáo viên',
            'ChuyenNganh.required' => 'Vui lòng chọn nhập chuyên ngành giảng dạy',
            'Sdt.required' => 'Vui lòng chọn nhập số điện thoại',
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
            'bangcapcanbo',
        ])->findOrFail($maGV);

        $giangdays = GiangDay::where('MaGV', $maGV)->get();

        return view('quanly_nhansu.giaovien.show', compact('giaovien', 'giangdays'));
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
            'MaGV' => 'required|unique:giaovien,MaGV,' . $maGV . ',MaGV',
            'HoTenGV' => 'required',
            'Email' => 'required|email|unique:giaovien,Email,' . $maGV . ',MaGV',
            'GioiTinh' => 'required',
            'LoaiGV' => 'required|in:CoHuu,MoiGiang',
        ], [
            'MaGV.required' => 'Vui lòng nhập mã giáo viên',
            'MaGV.unique' => 'Mã giáo viên này đã tồn tại trong hệ thống.',
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
    public function dongBoTaiKhoanGVLDAP()
    {
        $giaoViens = GiaoVien::whereNull('EmailCUSC')->get();
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

                if (empty($email)) {
                    throw new \Exception("Không tạo được email CUSC cho giáo viên {$giaoVien->HoTenGV}");
                }

                // Tạo tài khoản trong bảng ldap_accounts
                $ldapAccount = LdapAccount::create([
                    'MaTaiKhoan' => $giaoVien->MaGV,
                    'username' => $email,
                    'email' => $fullEmail,
                    'full_name' => $giaoVien->HoTenGV,
                    'initial_password' => $password,
                    'role' => 'teacher',
                    'is_sent' => false,
                    'is_active' => true
                ]);

                // Cập nhật email trong bảng giao_viens
                $giaoVien->update([
                    'EmailCUSC' => $fullEmail
                ]);

                DB::commit();
                $successCount++;

            } catch (\Exception $e) {
                DB::rollBack();
                $errorCount++;
                $errorDetails[] = [
                    'MaTaiKhoan' => $giaoVien->MaGV,
                    'ho_ten' => $giaoVien->HoTenGV,
                    'error_message' => $e->getMessage()
                ];
            }
        }

        $message = "Đồng bộ hoàn tất. Thành công: $successCount, Lỗi: $errorCount";
        return redirect()->route('giaovien.index')
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
            ->where('role', 'teacher')
            ->get();

        return view('quanly_nhansu.giaovien.list_account', compact('ldapAccounts'));
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
                Log::error('Lỗi gửi email LDAP cho giáo viên', [
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
    public function editLdapAccount($id)
    {
        $ldapAccount = LdapAccount::findOrFail($id);
        return view('quanly_nhansu.giaovien.account.edit_account', compact('ldapAccount'));
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
            return redirect()->route('giaovien.ldap.account.list')
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
            return redirect()->route('giaovien.ldap.account.list')
                ->with('success', 'Xóa tài khoản thành công');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
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
