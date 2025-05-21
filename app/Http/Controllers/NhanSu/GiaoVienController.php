<?php

namespace App\Http\Controllers\NhanSu;

use App\Http\Controllers\Controller;
use App\Models\giaovien;
use App\Models\hocvi;
use App\Models\bangcapcanbo;
use App\Models\chucvu;
use App\Models\donvi;
use App\Imports\GiaoVienImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
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
}
