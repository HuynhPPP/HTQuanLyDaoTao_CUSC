<?php

namespace App\Http\Controllers\Facilities;
use App\Http\Controllers\Controller;
use App\Models\lophoc;
use Illuminate\Http\Request;
use App\Models\sinhvien;
use App\Models\danhsachsv;
use \Illuminate\Support\Facades\DB;

class LopHocController extends Controller
{
    public function index()
    {
        $lophocs = lophoc::paginate(10);
        return view('quanly_cosovatchat.lophoc.index', compact('lophocs'));
    }

    public function create()
    {
        return view('quanly_cosovatchat.lophoc.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'MaLop' => 'required|unique:lophoc,MaLop',
            'TenLop' => 'required',
        ]);
        lophoc::create($request->all());
        return redirect()->route('lophoc.index')->with('success', 'Thêm lớp học thành công');
    }

    public function show($maLop)
    {
        $lophoc = lophoc::findOrFail($maLop);
        return view('quanly_cosovatchat.lophoc.show', compact('lophoc'));
    }

    public function edit($maLop)
    {
        $lophoc = lophoc::where('MaLop', $maLop)->firstOrFail();
        return view('quanly_cosovatchat.lophoc.edit', compact('lophoc'));
    }

    public function update(Request $request, $maLop)
    {
        $lophoc = lophoc::findOrFail($maLop);
        $lophoc->update($request->all());
        return redirect()->route('lophoc.index')->with('success', 'Cập nhật lớp học thành công');
    }

    public function destroy($maLop)
    {
        $lophoc = lophoc::findOrFail($maLop);
        $lophoc->delete();
        return redirect()->route('lophoc.index')->with('success', 'Xóa lớp học thành công');
    }

    public function addStudentForm($maLop)
    {
        $lophoc = lophoc::findOrFail($maLop);
        $sinhviens = sinhvien::all();
        $existingStudents = danhsachsv::where('MaLop', $maLop)->pluck('MaSV')->toArray();

        return view('quanly_cosovatchat.lophoc.add-student', compact('lophoc', 'sinhviens', 'existingStudents'));
    }

    public function addStudent(Request $request, $maLop)
    {
        $request->validate([
            'MaSV' => 'required|array',
            'MaSV.*' => 'exists:sinhvien,MaSV'
        ], [
            'MaSV.required' => 'Vui lòng chọn ít nhất một sinh viên.',
            'MaSV.array' => 'Dữ liệu sinh viên không hợp lệ.',
            'MaSV.*.exists' => 'Một hoặc nhiều mã sinh viên không tồn tại trong hệ thống.'
        ]);


        $successCount = 0;
        $errorCount = 0;

        foreach ($request->MaSV as $maSV) {
            // Kiểm tra sinh viên đã tồn tại trong lớp chưa
            $exists = danhsachsv::where('MaLop', $maLop)
                ->where('MaSV', $maSV)
                ->exists();

            if (!$exists) {
                // Thêm sinh viên vào lớp
                danhsachsv::create([
                    'MaLop' => $maLop,
                    'MaSV' => $maSV
                ]);
                $successCount++;
            } else {
                $errorCount++;
            }
        }

        $message = "Đã thêm thành công $successCount sinh viên vào lớp.";
        if ($errorCount > 0) {
            $message .= " Có $errorCount sinh viên đã tồn tại trong lớp.";
        }

        return redirect()->route('lophoc.show', $maLop)->with('success', $message);
    }

    public function removeSinhVien($malop, $masv)
    {
        try {
            $lophoc = LopHoc::findOrFail($malop);
            $sinhvien = SinhVien::findOrFail($masv);

            // Xóa sinh viên khỏi lớp học
            \Illuminate\Support\Facades\DB::table('danhsachsv')
                ->where('MaLop', $malop)
                ->where('MaSV', $masv)
                ->delete();

            return redirect()->route('lophoc.show', $malop)
                ->with('success', 'Đã xóa sinh viên khỏi lớp thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa sinh viên khỏi lớp');
        }
    }
}