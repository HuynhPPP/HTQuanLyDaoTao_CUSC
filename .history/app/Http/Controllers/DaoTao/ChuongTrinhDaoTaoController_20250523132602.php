<?php

namespace App\Http\Controllers\DaoTao;

use App\Http\Controllers\Controller;
use App\Models\ChuongTrinh;
use App\Models\khoadaotao;
use App\Models\monhoc;
use App\Models\ChuongTrinhMonHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChuongTrinhDaoTaoController extends Controller
{
    public function index()
    {
        $chuongTrinhs = ChuongTrinh::get();
        return view('quanly_daotao.chuongtrinhdaotao.index', compact('chuongTrinhs'));
    }

    public function create()
    {
        $khoadaotaos = khoadaotao::all();
        return view('quanly_daotao.chuongtrinhdaotao.create', compact('khoadaotaos'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'MaChuongTrinh' => 'required|unique:chuongtrinh|max:12',
            'TenChuongTrinh' => 'required',
            'PhienBan' => 'nullable|max:12',
            'NgayTrienKhaiPB' => 'nullable|date',
            'TenKhoaDaoTao' => 'required',
        ], [
            'MaChuongTrinh.required' => 'Mã chương trình không được để trống',
            'TenKhoaDaoTao.required' => 'Tên khoá đào tạo không được để trống',
            'TenChuongTrinh.required' => 'Tên chương trình đào tạo không được để trống',
            'MaChuongTrinh.unique' => 'Mã chương trình đã tồn tại trong hệ thống',
            'MaChuongTrinh.max' => 'Mã chương trình không được vượt quá 12 ký tự'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            ChuongTrinh::create($request->all());
            return redirect()->route('chuongtrinh.index')
                ->with('success', 'Thêm chương trình đào tạo thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function edit($maChuongTrinh)
    {
        $chuongTrinh = ChuongTrinh::findOrFail($maChuongTrinh);
        $khoadaotaos = khoadaotao::all();
        return view('quanly_daotao.chuongtrinhdaotao.edit', compact('chuongTrinh', 'khoadaotaos'));
    }

    public function update(Request $request, $maChuongTrinh)
    {
        $validator = Validator::make($request->all(), [
            'MaChuongTrinh' => 'required',
            'TenChuongTrinh' => 'required',
            'PhienBan' => 'nullable|max:12',
            'NgayTrienKhaiPB' => 'nullable|date',
            'TenKhoaDaoTao' => 'required',
        ],messages: [
            'MaChuongTrinh.required' => 'Mã chương trình không được để trống',
            'TenKhoaDaoTao.required' => 'Tên khoá đào tạo không được để trống',
            'TenChuongTrinh.required' => 'Tên chương trình đào tạo không được để trống'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $chuongTrinh = ChuongTrinh::findOrFail($maChuongTrinh);
            $chuongTrinh->update($request->all());

            return redirect()->route('chuongtrinh.index')
                ->with('success', 'Cập nhật chương trình đào tạo thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy($maChuongTrinh)
    {
        try {
            $chuongTrinh = ChuongTrinh::findOrFail($maChuongTrinh);
            $chuongTrinh->delete();
            return redirect()->route('chuongtrinh.index')
                ->with('success', 'Xóa chương trình đào tạo thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Không thể xóa chương trình đào tạo: ' . $e->getMessage());
        }
    }

    public function showMonHoc($maChuongTrinh)
    {
        $chuongTrinh = ChuongTrinh::findOrFail($maChuongTrinh);
        $monHocList = MonHoc::all();
        $monHocDaGan = ChuongTrinhMonHoc::where('MaChuongTrinh', $maChuongTrinh)->get();
        
        return view('quanly_daotao.chuongtrinhdaotao.monhoc', compact('chuongTrinh', 'monHocList', 'monHocDaGan'));
    }

    public function storeMonHoc(Request $request, $maChuongTrinh)
    {
        $validator = Validator::make($request->all(), [
            'TenMH' => 'required|exists:monhoc,TenMH'
        ], [
            'TenMH.required' => 'Vui lòng chọn môn học',
            'TenMH.exists' => 'Môn học không tồn tại trong hệ thống'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            ChuongTrinhMonHoc::create([
                'MaChuongTrinh' => $maChuongTrinh,
                'TenMH' => $request->TenMH
            ]);

            return redirect()->route('chuongtrinh.monhoc', $maChuongTrinh)
                ->with('success', 'Thêm môn học vào chương trình thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroyMonHoc($maChuongTrinh, $tenMH)
    {
        try {
            ChuongTrinhMonHoc::where('MaChuongTrinh', $maChuongTrinh)
                ->where('TenMH', $tenMH)
                ->delete();

            return redirect()->route('chuongtrinh.monhoc', $maChuongTrinh)
                ->with('success', 'Xóa môn học khỏi chương trình thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Không thể xóa môn học: ' . $e->getMessage());
        }
    }
}
