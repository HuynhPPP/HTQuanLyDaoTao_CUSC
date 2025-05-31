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
use Adldap\Laravel\Facades\Adldap;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
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
        // Lấy tất cả sinh viên chưa có tài khoản LDAP
        $sinhViens = SinhVien::whereNull('EmailCUSC')->orWhereNull('password_CUSC')->get();

        foreach ($sinhViens as $sinhVien) {
            // Tạo email CUSC
            $email = $this->taoEmailCUSC($sinhVien);

            // Tạo mật khẩu mạnh
            $password = $this->taoMatKhauManh($sinhVien);

            try {
                // Kết nối LDAP
                $ldap = Adldap::connect();

                // Tạo user mới trong AD
                $user = Adldap::make()->user([
                    'cn' => $sinhVien->HoTen,
                    'sAMAccountName' => $email,
                    'userPrincipalName' => $email . '@cusc.ctu.vn',
                    'mail' => $email . '@cusc.ctu.vn',
                    'givenName' => $sinhVien->HoTen,
                    'displayName' => $sinhVien->HoTen
                ]);

                // Đặt mật khẩu và kích hoạt tài khoản
                $user->setPassword($password);
                $user->setUserAccountControl(512); // Tài khoản bình thường, không bị khóa

                // Thêm vào group Student
                $studentGroup = Adldap::search()->groups()->find('CN=Student,CN=Users,DC=cusc,DC=ctu,DC=vn');
                $user->addGroup($studentGroup);

                // Lưu thông tin vào CSDL
                $sinhVien->update([
                    'EmailCUSC' => $email,
                    'password_CUSC' => Hash::make($password)
                ]);

            } catch (\Exception $e) {
                // Ghi log lỗi
                Log::error('Đồng bộ tài khoản LDAP lỗi: ' . $e->getMessage());
            }
        }
    }

    private function taoEmailCUSC($sinhVien)
    {
        // Loại bỏ dấu và chuyển sang chữ thường
        $hoTenKhongDau = $this->loaiBoKyTuDacBiet($sinhVien->HoTen);

        // Tạo email từ mã sinh viên và tên
        $email = strtolower($sinhVien->MaSV . substr($hoTenKhongDau, 0, 5));

        return $email;
    }

    private function taoMatKhauManh($sinhVien)
    {
        // Tạo mật khẩu phức tạp
        $kyTuDacBiet = ['!', '@', '#', '$', '%', '^', '&', '*'];
        $matKhau =
            Str::upper(substr($sinhVien->HoTen, 0, 2)) .
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
}
