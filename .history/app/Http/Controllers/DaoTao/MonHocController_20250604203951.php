<?php

namespace App\Http\Controllers\DaoTao;

use App\Http\Controllers\Controller;
use App\Models\MonHoc;
use App\Models\giaovien;
use App\Models\HinhThucDanhGia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MonHocController extends Controller
{
    public function index()
    {
        $monhocs = MonHoc::get();
        return view('quanly_daotao.monhoc.index', compact('monhocs'));
    }
    public function create()
    {
        return view('quanly_daotao.monhoc.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TenMH' => 'required|unique:monhoc|max:255',
            'MaMH' => 'required|unique:monhoc|max:12',
            'GioGoc' => 'nullable|integer',
            'GioTrienKhai' => 'nullable|integer',
            'TietLT' => 'nullable|boolean',
            'TietTH' => 'nullable|boolean',
            'TietLTvaTH' => 'nullable|boolean',
        ], [
            'TenMH.required' => 'Tên môn học không được để trống',
            'MaMH.required' => 'Mã môn học không được để trống',
            'TenMH.unique' => 'Tên môn học đã tồn tại',
            'MaMH.unique' => 'Mã môn học đã tồn tại',
            'MaMH.max' => 'Mã môn học không được vượt quá 12 ký tự'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            MonHoc::create($request->all());
            return redirect()->route('monhoc.index')
                ->with('success', 'Thêm môn học thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    public function edit($tenMH)
    {
        $monhoc = MonHoc::where('TenMH', $tenMH)->firstOrFail();
        return view('quanly_daotao.monhoc.edit', compact('monhoc'));
    }
    public function update(Request $request, $tenMH)
    {
        $validator = Validator::make($request->all(), [
            'MaMH' => 'required|max:12',
            'TenMH' => 'required',
            'GioGoc' => 'nullable|integer',
            'GioTrienKhai' => 'nullable|integer',
            'TietLT' => 'nullable',
            'TietTH' => 'nullable',
            'TietLTvaTH' => 'nullable',
        ], [
            'MaMH.required' => 'Mã môn học không được bỏ trống.',
            'TenMH.required' => 'Tên môn học không được bỏ trống.',
            'MaMH.max' => 'Mã môn học không được vượt quá 12 ký tự.',
            'GioGoc.integer' => 'Giờ gốc phải là số nguyên.',
            'GioTrienKhai.integer' => 'Giờ triển khai phải là số nguyên.',
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $monhoc = MonHoc::where('TenMH', $tenMH)->firstOrFail();
            $monhoc->update($request->all());

            return redirect()->route('monhoc.index')
                ->with('success', 'Cập nhật môn học thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    public function destroy($tenMH)
    {
        try {
            $monhoc = MonHoc::where('TenMH', $tenMH)->firstOrFail();
            $monhoc->delete();
            return redirect()->route('monhoc.index')
                ->with('success', 'Xóa môn học thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Không thể xóa môn học: ' . $e->getMessage());
        }
    }
    public function addTeacherForm($tenMH)
    {
        $monhoc = monhoc::findOrFail($tenMH);
        $giaoviens = giaovien::all();
        $existingTeachers = $monhoc->giangViens->pluck('MaGV')->toArray();

        return view('quanly_daotao.monhoc.add-teacher', compact('monhoc', 'giaoviens', 'existingTeachers'));
    }

    public function storeTeacher(Request $request, $tenMH)
    {
        $request->validate([
            'MaGV' => 'required|exists:giaovien,MaGV',
            'NgayBatDau' => 'nullable|date',
            'NgayKetThuc' => 'nullable|date|after:NgayBatDau',
        ]);

        $monhoc = monhoc::findOrFail($tenMH);

        // Attach the teacher to the subject with additional pivot data
        $monhoc->giangViens()->attach($request->MaGV, [
            'NgayBatDau' => $request->NgayBatDau,
            'NgayKetThuc' => $request->NgayKetThuc,
            'GhiChu' => $request->GhiChu
        ]);

        return redirect()->route('monhoc.index')->with('success', 'Phân công giảng viên thành công');
    }
    public function removeTeacher($maLop, $maGV)
    {
        try {
            $lophoc = lophoc::findOrFail($maLop);
            $lophoc->giangViens()->detach($maGV);

            return redirect()->route('lophoc.show', $maLop)
                ->with('success', 'Đã xóa phân công giảng viên thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa phân công giảng viên');
        }
    }
}