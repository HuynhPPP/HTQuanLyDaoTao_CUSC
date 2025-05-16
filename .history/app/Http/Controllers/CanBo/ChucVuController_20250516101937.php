<?php

namespace App\Http\Controllers\CanBo;

use App\Http\Controllers\Controller;
use App\Models\ChucVu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChucVuController extends Controller
{
    public function index()
    {
        $chucVus = ChucVu::paginate(10);
        return view('canbo.chucvu.index', compact('chucVus'));
    }

    public function create()
    {
        return view('canbo.chucvu.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TenChucVu' => 'required|unique:chucvu|max:30',
            'ThoiGianBatDauCV' => 'nullable|max:50',
            'ThoiGianKTCV' => 'nullable|max:50'
        ], [
            'TenChucVu.required' => 'Tên chức vụ không được để trống',
            'TenChucVu.unique' => 'Tên chức vụ đã tồn tại',
            'TenChucVu.max' => 'Tên chức vụ không được vượt quá 30 ký tự'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            ChucVu::create($request->all());
            return redirect()->route('chucvu.index')
                ->with('success', 'Thêm chức vụ thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function edit($tenChucVu)
    {
        $chucVu = ChucVu::findOrFail($tenChucVu);
        return view('canbo.chucvu.edit', compact('chucVu'));
    }

    public function update(Request $request, $tenChucVu)
    {
        $validator = Validator::make($request->all(), [
            'ThoiGianBatDauCV' => 'nullable|max:50',
            'ThoiGianKTCV' => 'nullable|max:50'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $chucVu = ChucVu::findOrFail($tenChucVu);
            $chucVu->update($request->all());
            return redirect()->route('chucvu.index')
                ->with('success', 'Cập nhật chức vụ thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy($tenChucVu)
    {
        try {
            $chucVu = ChucVu::findOrFail($tenChucVu);
            $chucVu->delete();
            return redirect()->route('chucvu.index')
                ->with('success', 'Xóa chức vụ thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Không thể xóa chức vụ: ' . $e->getMessage());
        }
    }
} 