<?php

namespace App\Http\Controllers\CanBo;

use App\Http\Controllers\Controller;
use App\Models\giaovien;
use App\Models\hocvi;
use App\Models\bangcapcanbo;
use App\Models\chucvu;
use App\Models\donvi;
use Illuminate\Http\Request;
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

        return view('giaovien.index', compact('giaoviens'));
    }

    public function create()
    {
        $hocvis = hocvi::all();
        $bangcaps = bangcapcanbo::all();
        $chucvus = chucvu::all();
        $donvis = donvi::all();

        return view('giaovien.create', compact(
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

    // Các phương thức khác như show, edit, update, destroy tương tự
}
