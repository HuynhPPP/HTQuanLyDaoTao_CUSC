<?php

namespace App\Http\Controllers\CanBo;

use App\Http\Controllers\Controller;
use App\Models\HocVi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HocViController extends Controller
{
    public function index()
    {
        $hocVis = HocVi::paginate(10);
        return view('canbo.hocvi.index', compact('hocVis'));
    }

    public function create()
    {
        return view('canbo.hocvi.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'MaHV' => 'required|unique:hocvi|max:12',
            'TenHocVi' => 'nullable|max:50',
            'NganhHoc' => 'nullable|max:255',
            'ChuyenNganh' => 'nullable|max:255',
            'CoSoDaoTao' => 'nullable|max:255',
            'NamCap' => 'nullable|date',
            'HinhThucDaoTao' => 'nullable|max:255'
        ], [
            'MaHV.required' => 'Mã học vị không được để trống',
            'MaHV.unique' => 'Mã học vị đã tồn tại',
            'MaHV.max' => 'Mã học vị không được vượt quá 12 ký tự'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            HocVi::create($request->all());
            return redirect()->route('hocvi.index')
                ->with('success', 'Thêm học vị thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function edit($maHV)
    {
        $hocVi = HocVi::findOrFail($maHV);
        return view('canbo.hocvi.edit', compact('hocVi'));
    }

    public function update(Request $request, $maHV)
    {
        $validator = Validator::make($request->all(), [
            'TenHocVi' => 'nullable|max:50',
            'NganhHoc' => 'nullable|max:255',
            'ChuyenNganh' => 'nullable|max:255',
            'CoSoDaoTao' => 'nullable|max:255',
            'NamCap' => 'nullable|date',
            'HinhThucDaoTao' => 'nullable|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $hocVi = HocVi::findOrFail($maHV);
            $hocVi->update($request->all());
            return redirect()->route('hocvi.index')
                ->with('success', 'Cập nhật học vị thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy($maHV)
    {
        try {
            $hocVi = HocVi::findOrFail($maHV);
            $hocVi->delete();
            return redirect()->route('hocvi.index')
                ->with('success', 'Xóa học vị thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Không thể xóa học vị: ' . $e->getMessage());
        }
    }
} 