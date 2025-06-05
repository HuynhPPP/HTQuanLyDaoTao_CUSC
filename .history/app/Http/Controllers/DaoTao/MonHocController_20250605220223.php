<?php

namespace App\Http\Controllers\DaoTao;

use App\Http\Controllers\Controller;
use App\Models\GiangDay;
use App\Models\MonHoc;
use App\Models\giaovien;
use App\Models\lophoc;
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
    public function edit($MaMH)
    {
        $monhoc = MonHoc::where('MaMH', $MaMH)->firstOrFail();
        return view('quanly_daotao.monhoc.edit', compact('monhoc'));
    }
    public function update(Request $request, $MaMH)
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
            $monhoc = MonHoc::where('MaMH', $MaMH)->firstOrFail();
            $monhoc->update($request->all());

            return redirect()->route('monhoc.index')
                ->with('success', 'Cập nhật môn học thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    public function destroy($MaMH)
    {
        try {
            $monhoc = MonHoc::where('MaMH', $MaMH)->firstOrFail();
            $monhoc->delete();
            return redirect()->route('monhoc.index')
                ->with('success', 'Xóa môn học thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Không thể xóa môn học: ' . $e->getMessage());
        }
    }
    public function addTeacherForm($maMH)
    {
        $monhoc = MonHoc::where('MaMH', $maMH)->firstOrFail();
        $giaoviens = giaovien::all();
        $lops = lophoc::all();
        $existingTeachers = $monhoc->giangViens->pluck('MaGV')->toArray();

        return view('quanly_daotao.monhoc.add-teacher', compact('monhoc', 'giaoviens', 'existingTeachers'));
    }
    public function storeTeacher(Request $request, $MaMH)
    {
        $request->validate([
            'MaGV' => 'required|exists:giaovien,MaGV',
            'MaLop' => 'required|exists:lop,MaLop',
            'NgayBatDau' => 'nullable|date',
            'NgayKetThuc' => 'nullable|date|after:NgayBatDau',
        ], [
            'MaGV.required' => 'Vui lòng chọn giảng viên.',
            'MaGV.exists' => 'Giảng viên không tồn tại trong hệ thống.',
            'NgayBatDau.date' => 'Ngày bắt đầu không hợp lệ.',
            'NgayKetThuc.date' => 'Ngày kết thúc không hợp lệ.',
            'NgayKetThuc.after' => 'Ngày kết thúc phải sau ngày bắt đầu.'
        ]);

        // Chèn bản ghi mới
        \DB::table('giangday')->insert([
            'MaGV' => $request->MaGV,
            'MaMH' => $MaMH,
            'NgayBatDau' => $request->NgayBatDau,
            'NgayKetThuc' => $request->NgayKetThuc,
            'GhiChu' => $request->GhiChu,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('monhoc.index')->with('success', 'Phân công giảng viên thành công');
    }
    public function removeTeacher($MaMH, $maGV)
    {
        // Kiểm tra tồn tại của bản ghi
        $exists = \DB::table('giangday')
            ->where('MaMH', $MaMH)
            ->where('MaGV', $maGV)
            ->exists();

        if (!$exists) {
            return redirect()->route('monhoc.index')
                ->with('error', 'Không tìm giảng viên phân công');
        }

        // Xoá bản ghi
        \DB::table('giangday')
            ->where('MaMH', $MaMH)
            ->where('MaGV', $maGV)
            ->delete();

        return redirect()->route('monhoc.index')
            ->with('success', 'Đã xoá giảng viên khỏi môn học thành công');
    }
    public function editTeacherAssignment($MaMH, $maGV)
    {
        $monhoc = monhoc::findOrFail($MaMH);
        $giaoviens = giaovien::all();

        // Lấy thông tin giảng viên hiện tại
        $currentTeacher = $monhoc->giangViens()->where('giaovien.MaGV', $maGV)->first();

        if (!$currentTeacher) {
            return redirect()->route('monhoc.index')
                ->with('error', 'Không tìm thấy thông tin giảng viên');
        }

        $existingTeachers = $monhoc->giangViens->pluck('MaGV')->toArray();

        return view('quanly_daotao.monhoc.edit-teacher', compact(
            'monhoc',
            'giaoviens',
            'currentTeacher',
            'existingTeachers'
        ));
    }
    public function updateTeacherAssignment(Request $request, $MaMH, $maGV)
    {
        $request->validate([
            'MaGV' => 'required|exists:giaovien,MaGV',
            'NgayBatDau' => 'nullable|date',
            'NgayKetThuc' => 'nullable|date|after:NgayBatDau',
        ], [
            'MaGV.required' => 'Vui lòng chọn giảng viên.',
            'MaGV.exists' => 'Giảng viên không tồn tại trong hệ thống.',
            'NgayBatDau.date' => 'Ngày bắt đầu không hợp lệ.',
            'NgayKetThuc.date' => 'Ngày kết thúc không hợp lệ.',
            'NgayKetThuc.after' => 'Ngày kết thúc phải sau ngày bắt đầu.'
        ]);

        // Xoá bản ghi cũ
        \DB::table('giangday')
            ->where('MaMH', $MaMH)
            ->where('MaGV', $maGV)
            ->delete();

        // Chèn bản ghi mới
        \DB::table('giangday')->insert([
            'MaMH' => $MaMH,
            'MaGV' => $request->MaGV,
            'NgayBatDau' => $request->NgayBatDau,
            'NgayKetThuc' => $request->NgayKetThuc,
            'GhiChu' => $request->GhiChu,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('monhoc.index')
            ->with('success', 'Cập nhật phân công giảng viên thành công');
    }
}