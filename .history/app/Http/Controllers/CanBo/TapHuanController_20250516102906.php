<?php

namespace App\Http\Controllers\CanBo;

use App\Http\Controllers\Controller;
use App\Models\TapHuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TapHuanController extends Controller
{
    public function index()
    {
        $tapHuans = TapHuan::paginate(10);
        return view('canbo.taphuan.index', compact('tapHuans'));
    }

    public function create()
    {
        return view('canbo.taphuan.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'MaTapHuan' => 'required|unique:taphuan|max:12',
            'TenKhoaTapHuan' => 'nullable|max:30',
            'ThoiGianBatDau' => 'nullable|date',
            'ThoiGianKetThuc' => 'nullable|date|after_or_equal:ThoiGianBatDau',
            'DiaDiem' => 'nullable|max:20'
        ], [
            'MaTapHuan.required' => 'Mã tập huấn không được để trống',
            'MaTapHuan.unique' => 'Mã tập huấn đã tồn tại',
            'MaTapHuan.max' => 'Mã tập huấn không được vượt quá 12 ký tự',
            'ThoiGianKetThuc.after_or_equal' => 'Thời gian kết thúc phải sau hoặc bằng thời gian bắt đầu'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            TapHuan::create($request->all());
            return redirect()->route('taphuan.index')
                ->with('success', 'Thêm khóa tập huấn thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function edit($maTapHuan)
    {
        $tapHuan = TapHuan::findOrFail($maTapHuan);
        return view('canbo.taphuan.edit', compact('tapHuan'));
    }

    public function update(Request $request, $maTapHuan)
    {
        $validator = Validator::make($request->all(), [
            'TenKhoaTapHuan' => 'nullable|max:30',
            'ThoiGianBatDau' => 'nullable|date',
            'ThoiGianKetThuc' => 'nullable|date|after_or_equal:ThoiGianBatDau',
            'DiaDiem' => 'nullable|max:20'
        ], [
            'ThoiGianKetThuc.after_or_equal' => 'Thời gian kết thúc phải sau hoặc bằng thời gian bắt đầu'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $tapHuan = TapHuan::findOrFail($maTapHuan);
            $tapHuan->update($request->all());
            return redirect()->route('taphuan.index')
                ->with('success', 'Cập nhật khóa tập huấn thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy($maTapHuan)
    {
        try {
            $tapHuan = TapHuan::findOrFail($maTapHuan);
            $tapHuan->delete();
            return redirect()->route('taphuan.index')
                ->with('success', 'Xóa khóa tập huấn thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Không thể xóa khóa tập huấn: ' . $e->getMessage());
        }
    }
} 