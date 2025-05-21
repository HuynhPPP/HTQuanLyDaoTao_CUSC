<?php

namespace App\Http\Controllers\CanBo;
use App\Http\Controllers\Controller;
use App\Models\canbo;
use App\Models\hocvi;
use App\Models\bangcapcanbo;
use App\Models\chucvu;
use App\Models\donvi;
use App\Models\TapHuan;
use App\Imports\CanBoImport;
use Maatwebsite\Excel\Facades\Excel;
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

        return view('canbo.index', compact('canbos'));
    }

    public function create()
    {
        $hocvis = HocVi::all();
        $bangcaps = BangCapCanBo::all();
        $chucvus = ChucVu::all();
        $donvis = DonVi::all();
        $taphuans = TapHuan::all();
        return view('canbo.create', compact('hocvis', 'bangcaps', 'chucvus', 'donvis', 'taphuans'));
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
        return view('canbo.show', compact('canbo'));
    }

    public function edit($maCB)
    {
        $canbo = CanBo::where('MaCB', $maCB)->firstOrFail();
        $hocvis = HocVi::all();
        $bangcaps = BangCapCanBo::all();
        $chucvus = ChucVu::all();
        $donvis = DonVi::all();
        $taphuans = TapHuan::all();
        return view('canbo.edit', compact('canbo', 'hocvis', 'bangcaps', 'chucvus', 'donvis', 'taphuans'));
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
}
