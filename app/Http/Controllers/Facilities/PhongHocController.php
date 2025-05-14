<?php

namespace App\Http\Controllers\Facilities;
use App\Http\Controllers\Controller;
use App\Models\phonghoc;
use Illuminate\Http\Request;

class PhongHocController extends Controller
{
    public function index()
    {
        $phonghocs = phonghoc::paginate(10);
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