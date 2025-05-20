<?php

namespace App\Http\Controllers\Facilities;
use App\Http\Controllers\Controller;
use App\Models\lophoc;
use Illuminate\Http\Request;
use App\Models\sinhvien;
use App\Models\danhsachsv;

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
        dd($request->all());
        $request->validate([
            'MaSV' => 'required|exists:sinhvien,MaSV'
        ]);

        // Kiểm tra sinh viên đã tồn tại trong lớp chưa
        $exists = danhsachsv::where('MaLop', $maLop)
            ->where('MaSV', $request->MaSV)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Sinh viên đã tồn tại trong lớp này!');
        }

        // Thêm sinh viên vào lớp
        danhsachsv::create([
            'MaLop' => $maLop,
            'MaSV' => $request->MaSV
        ]);

        return redirect()->route('lophoc.show', $maLop)->with('success', 'Thêm sinh viên vào lớp thành công!');
    }
}