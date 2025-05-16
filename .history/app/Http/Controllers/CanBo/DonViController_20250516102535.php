<?php

namespace App\Http\Controllers\CanBo;

use App\Http\Controllers\Controller;
use App\Models\DonVi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DonViController extends Controller
{
    public function index()
    {
        $donVis = DonVi::paginate(10);
        return view('canbo.donvi.index', compact('donVis'));
    }

    public function create()
    {
        return view('canbo.donvi.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'MaDV' => 'required|unique:donvi|max:12',
            'TenDVHienTai' => 'nullable|max:255',
            'TenDVTungCongTac' => 'nullable|max:255'
        ], [
            'MaDV.required' => 'Mã đơn vị không được để trống',
            'MaDV.unique' => 'Mã đơn vị đã tồn tại',
            'MaDV.max' => 'Mã đơn vị không được vượt quá 12 ký tự'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DonVi::create($request->all());
            return redirect()->route('donvi.index')
                ->with('success', 'Thêm đơn vị thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function edit($maDV)
    {
        $donVi = DonVi::findOrFail($maDV);
        return view('canbo.donvi.edit', compact('donVi'));
    }

    public function update(Request $request, $maDV)
    {
        $validator = Validator::make($request->all(), [
            'TenDVHienTai' => 'nullable|max:255',
            'TenDVTungCongTac' => 'nullable|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $donVi = DonVi::findOrFail($maDV);
            $donVi->update($request->all());
            return redirect()->route('donvi.index')
                ->with('success', 'Cập nhật đơn vị thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy($maDV)
    {
        try {
            $donVi = DonVi::findOrFail($maDV);
            $donVi->delete();
            return redirect()->route('donvi.index')
                ->with('success', 'Xóa đơn vị thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Không thể xóa đơn vị: ' . $e->getMessage());
        }
    }
} 