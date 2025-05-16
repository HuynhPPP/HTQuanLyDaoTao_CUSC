<?php

namespace App\Http\Controllers\CanBo;

use App\Http\Controllers\Controller;
use App\Models\PhuTrach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PhuTrachController extends Controller
{
    public function index()
    {
        $phuTrachs = PhuTrach::paginate(10);
        return view('canbo.phutrach.index', compact('phuTrachs'));
    }

    public function create()
    {
        return view('canbo.phutrach.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'CongViecPhuTrach' => 'required|unique:phutrach|max:255',
            'MieuTaChiTiet' => 'nullable|max:255'
        ], [
            'CongViecPhuTrach.required' => 'Công việc phụ trách không được để trống',
            'CongViecPhuTrach.unique' => 'Công việc phụ trách đã tồn tại',
            'CongViecPhuTrach.max' => 'Công việc phụ trách không được vượt quá 255 ký tự'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            PhuTrach::create($request->all());
            return redirect()->route('phutrach.index')
                ->with('success', 'Thêm công việc phụ trách thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function edit($congViecPhuTrach)
    {
        $phuTrach = PhuTrach::findOrFail($congViecPhuTrach);
        return view('canbo.phutrach.edit', compact('phuTrach'));
    }

    public function update(Request $request, $congViecPhuTrach)
    {
        $validator = Validator::make($request->all(), [
            'MieuTaChiTiet' => 'nullable|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $phuTrach = PhuTrach::findOrFail($congViecPhuTrach);
            $phuTrach->update($request->all());
            return redirect()->route('phutrach.index')
                ->with('success', 'Cập nhật công việc phụ trách thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy($congViecPhuTrach)
    {
        try {
            $phuTrach = PhuTrach::findOrFail($congViecPhuTrach);
            $phuTrach->delete();
            return redirect()->route('phutrach.index')
                ->with('success', 'Xóa công việc phụ trách thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Không thể xóa công việc phụ trách: ' . $e->getMessage());
        }
    }
} 