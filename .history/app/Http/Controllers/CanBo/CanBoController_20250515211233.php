<?php

namespace App\Http\Controllers\CanBo;
use App\Http\Controllers\Controller;
use App\Models\canbo;
use App\Models\hocvi;
use App\Models\bangcapcanbo;
use App\Models\chucvu;
use App\Models\donvi;
use App\Models\TapHuan;
use App\Imports\CanBoImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class CanBoController extends Controller
{
    public function index() {
        $canbos = CanBo::with(['hocvi', 'bangcap', 'chucvu', 'donvi', 'taphuan'])->paginate(10);
        return view('canbo.index', compact('canbos'));
    }

    public function create() {
        $hocvis = HocVi::all();
        $bangcaps = BangCapCanBo::all();
        $chucvus = ChucVu::all();
        $donvis = DonVi::all();
        $taphuans = TapHuan::all();
        return view('canbo.create', compact('hocvis', 'bangcaps', 'chucvus', 'donvis', 'taphuans'));
    }

    public function store(Request $request) {
        $request->validate([
            'MaCB' => 'required|unique:canbo,MaCB',
            'HoTenCB' => 'required',
            'GioiTinh' => 'required',
            'Email' => 'required|email',
            'Sdt' => 'required',
        ]);
        
        CanBo::create($request->all());
        return redirect()->route('staff.index')->with('success', 'Thêm cán bộ thành công');
    }

    public function show($maCB) {
        $canbo = CanBo::with(['hocvi', 'bangcap', 'chucvu', 'donvi', 'taphuan'])->where('MaCB', $maCB)->firstOrFail();
        return view('canbo.show', compact('canbo'));
    }

    public function edit($maCB) {
        $canbo = CanBo::where('MaCB', $maCB)->firstOrFail();
        $hocvis = HocVi::all();
        $bangcaps = BangCapCanBo::all();
        $chucvus = ChucVu::all();
        $donvis = DonVi::all();
        $taphuans = TapHuan::all();
        return view('canbo.edit', compact('canbo', 'hocvis', 'bangcaps', 'chucvus', 'donvis', 'taphuans'));
    }

    public function update(Request $request, $maCB) {
        $request->validate([
            'HoTenCB' => 'required',
            'GioiTinh' => 'required',
            'Email' => 'required|email',
            'Sdt' => 'required',
        ]);
        
        $canbo = CanBo::where('MaCB', $maCB)->firstOrFail();
        $canbo->update($request->all());
        return redirect()->route('staff.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy($maCB) {
        $canbo = CanBo::where('MaCB', $maCB)->firstOrFail();
        $canbo->delete();
        return redirect()->route('staff.index')->with('success', 'Xóa cán bộ thành công');
    }
    
    // Thêm phương thức import dữ liệu từ Excel
    public function import(Request $request) {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ], [
            'file.required' => 'Vui lòng chọn file để import',
            'file.mimes' => 'File phải có định dạng Excel (.xlsx, .xls, .csv)',
        ]);

        Excel::import(new CanBoImport, $request->file('file'));

        if (session()->has('import_errors') && count(session('import_errors')) > 0) {
            return back()->with('warning', 'Đã import ' . session('import_success_count') . ' cán bộ, có một số lỗi:')
                        ->with('import_errors', session('import_errors'));
        }

        return back()->with('success', 'Đã import thành công ' . session('import_success_count') . ' cán bộ');
    }
}
