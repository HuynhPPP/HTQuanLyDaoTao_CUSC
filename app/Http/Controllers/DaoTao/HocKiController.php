<?php

namespace App\Http\Controllers\DaoTao;

use App\Http\Controllers\Controller;
use App\Models\HocKi;
use App\Models\ChuongTrinh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HocKiController extends Controller
{
    public function index()
    {
        $hockis = HocKi::with('chuongTrinh')->get();
        return view('quanly_daotao.hocki.index', compact('hockis'));
    }

    public function create()
    {
        $chuongtrinhs = ChuongTrinh::all();
        return view('quanly_daotao.hocki.create', compact('chuongtrinhs'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'MaHK' => 'required|unique:hocki|max:50',
            'TenHK' => 'required|max:30',
            'TongGioGoc' => 'nullable|integer',
            'TongGioTrienKhai' => 'nullable|integer',
            'MaChuongTrinh' => 'required|exists:chuongtrinh,MaChuongTrinh',
        ], [
            'MaHK.required' => 'Mã học kỳ không được để trống',
            'MaHK.unique' => 'Mã học kỳ đã tồn tại trong hệ thống',
            'MaHK.max' => 'Mã học kỳ không được vượt quá 50 ký tự',
            'TenHK.required' => 'Tên học kỳ không được để trống',
            'TenHK.max' => 'Tên học kỳ không được vượt quá 30 ký tự',
            'MaChuongTrinh.required' => 'Mã chương trình không được để trống',
            'MaChuongTrinh.exists' => 'Mã chương trình không tồn tại trong hệ thống'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            HocKi::create($request->all());
            return redirect()->route('hocki.index')
                ->with('success', 'Thêm học kỳ thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function edit($maHK)
    {
        $hocki = HocKi::findOrFail($maHK);
        $chuongtrinhs = ChuongTrinh::all();
        return view('quanly_daotao.hocki.edit', compact('hocki', 'chuongtrinhs'));
    }

    public function update(Request $request, $maHK)
    {
        $validator = Validator::make($request->all(), [
            'MaHK' => 'required|max:50',
            'TenHK' => 'required|max:30',
            'TongGioGoc' => 'nullable|integer',
            'TongGioTrienKhai' => 'nullable|integer',
            'MaChuongTrinh' => 'required|exists:chuongtrinh,MaChuongTrinh',
        ], [
            'MaHK.required' => 'Mã học kỳ không được để trống',
            'MaHK.max' => 'Mã học kỳ không được vượt quá 50 ký tự',
            'TenHK.required' => 'Tên học kỳ không được để trống',
            'TenHK.max' => 'Tên học kỳ không được vượt quá 30 ký tự',
            'MaChuongTrinh.required' => 'Mã chương trình không được để trống',
            'MaChuongTrinh.exists' => 'Mã chương trình không tồn tại trong hệ thống'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $hocki = HocKi::findOrFail($maHK);
            $hocki->update($request->all());

            return redirect()->route('hocki.index')
                ->with('success', 'Cập nhật học kỳ thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy($maHK)
    {
        try {
            $hocki = HocKi::findOrFail($maHK);
            $hocki->delete();
            return redirect()->route('hocki.index')
                ->with('success', 'Xóa học kỳ thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Không thể xóa học kỳ: ' . $e->getMessage());
        }
    }
}