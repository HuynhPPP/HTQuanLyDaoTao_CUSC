<?php

namespace App\Http\Controllers\Facilities;
use App\Http\Controllers\Controller;
use App\Models\phonghoc;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\danhsachphong;

class PhongHocController extends Controller
{
    public function index(Request $request)
    {
        $query = phonghoc::query();

        // Lọc theo ngày
        if ($request->has('ngay')) {
            $ngay = Carbon::parse($request->ngay);
            $query->whereDoesntHave('danhsachphong', function($q) use ($ngay) {
                $q->whereDate('NgaySuDung', $ngay);
            });
        }

        // Lọc theo tuần
        if ($request->has('tuan')) {
            $tuan = Carbon::parse($request->tuan);
            $startOfWeek = $tuan->startOfWeek();
            $endOfWeek = $tuan->copy()->endOfWeek();
            $query->whereDoesntHave('danhsachphong', function($q) use ($startOfWeek, $endOfWeek) {
                $q->whereBetween('NgaySuDung', [$startOfWeek, $endOfWeek]);
            });
        }

        // Lọc theo ca
        if ($request->has('ca')) {
            $query->whereDoesntHave('danhsachphong', function($q) use ($request) {
                $q->where('Ca', $request->ca);
            });
        }

        // Lọc theo trạng thái
        if ($request->has('trang_thai')) {
            $query->where('TrangThai', $request->trang_thai);
        }

        // Eager load mối quan hệ danhsachphong để kiểm tra trạng thái sử dụng
        $phonghocs = $query->with('danhsachphong')->paginate(10);

        return view('quanly_cosovatchat.phonghoc.index', compact('phonghocs'));
    }

    public function create()
    {
        return view('quanly_cosovatchat.phonghoc.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'TenPhong' => 'required|unique:phonghoc,TenPhong',
            'LoaiPhong' => 'required',
            'SucChua' => 'nullable|integer|min:0',
            'TrangThai' => 'required|in:Trống,Đang sử dụng,Bảo trì',
        ]);
        phonghoc::create($request->all());
        return redirect()->route('phonghoc.index')->with('success', 'Thêm phòng học thành công');
    }

    public function show($tenPhong)
    {
        $phonghoc = phonghoc::findOrFail($tenPhong);
        return view('quanly_cosovatchat.phonghoc.show', compact('phonghoc'));
    }

    public function edit($tenPhong)
    {
        $phonghoc = phonghoc::where('TenPhong', $tenPhong)->firstOrFail();
        return view('quanly_cosovatchat.phonghoc.edit', compact('phonghoc'));
    }

    public function update(Request $request, $tenPhong)
    {
        $request->validate([
            'LoaiPhong' => 'required',
            'SucChua' => 'nullable|integer|min:0',
            'TrangThai' => 'required|in:Trống,Đang sử dụng,Bảo trì',
        ]);
        
        $phonghoc = phonghoc::findOrFail($tenPhong);
        $phonghoc->update($request->all());
        return redirect()->route('phonghoc.index')->with('success', 'Cập nhật phòng học thành công');
    }

    public function destroy($tenPhong)
    {
        try {
            // Xoá các bản ghi liên quan trong danhsachphong trước (nếu muốn tự động)
            // \App\Models\danhsachphong::where('TenPhong', $tenPhong)->delete();

            $phonghoc = phonghoc::findOrFail($tenPhong);
            $phonghoc->delete();

            return redirect()->route('phonghoc.index')->with('success', 'Xóa phòng học thành công');
        } catch (\Illuminate\Database\QueryException $e) {
            // Kiểm tra mã lỗi 1451 (ràng buộc khoá ngoại)
            if ($e->getCode() == 23000) {
                return redirect()->route('phonghoc.index')->with('error', 'Không thể xóa phòng học vì đang được gán cho lớp. Vui lòng xóa các gán phòng trước.');
            }
            // Lỗi khác
            return redirect()->route('phonghoc.index')->with('error', 'Đã xảy ra lỗi khi xóa phòng học.');
        }
    }
}