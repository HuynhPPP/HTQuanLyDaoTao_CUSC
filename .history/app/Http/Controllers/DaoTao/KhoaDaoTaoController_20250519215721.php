<?php

namespace App\Http\Controllers\DaoTao;

use App\Http\Controllers\Controller;
use App\Models\KhoaDaoTao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KhoaDaoTaoController extends Controller
{
    public function index()
    {
        $khoadaotao = KhoaDaoTao::all();
        return view('quanly_daotao.khoadaotao.index', compact('khoadaotao'));
    }

    public function create()
    {
        return view('quanly_daotao.khoadaotao.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TenKhoaDaoTao' => 'required|unique:khoadaotao,TenKhoaDaoTao',
            'ThoiGianDaoTao' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        KhoaDaoTao::create([
            'TenKhoaDaoTao' => $request->TenKhoaDaoTao,
            'ThoiGianDaoTao' => $request->ThoiGianDaoTao
        ]);

        return redirect()->route('khoadaotao.index')->with('success', 'Thêm khóa đào tạo thành công');
    }

    public function edit($tenKhoaDaoTao)
    {
        $khoadaotao = KhoaDaoTao::where('TenKhoaDaoTao', $tenKhoaDaoTao)->firstOrFail();
        return view('daotao.khoadaotao.edit', compact('khoadaotao'));
    }

    public function update(Request $request, $tenKhoaDaoTao)
    {
        $validator = Validator::make($request->all(), [
            'TenKhoaDaoTao' => 'required|unique:khoadaotao,TenKhoaDaoTao,' . $tenKhoaDaoTao . ',TenKhoaDaoTao',
            'ThoiGianDaoTao' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $khoadaotao = KhoaDaoTao::where('TenKhoaDaoTao', $tenKhoaDaoTao)->firstOrFail();
        $khoadaotao->update([
            'TenKhoaDaoTao' => $request->TenKhoaDaoTao,
            'ThoiGianDaoTao' => $request->ThoiGianDaoTao
        ]);

        return redirect()->route('khoadaotao.index')->with('success', 'Cập nhật khóa đào tạo thành công');
    }

    public function destroy($tenKhoaDaoTao)
    {
        $khoadaotao = KhoaDaoTao::where('TenKhoaDaoTao', $tenKhoaDaoTao)->firstOrFail();
        $khoadaotao->delete();

        return redirect()->route('khoadaotao.index')->with('success', 'Xóa khóa đào tạo thành công');
    }
}