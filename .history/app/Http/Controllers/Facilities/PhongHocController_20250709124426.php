<?php

namespace App\Http\Controllers\Facilities;
use App\Http\Controllers\Controller;
use App\Models\phonghoc;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\danhsachphong;
use App\Models\khunggio;

class PhongHocController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->input('ngay') ? Carbon::parse($request->input('ngay')) : Carbon::now();

        // Lấy tất cả khung giờ thực tế
        $khunggios = KhungGio::whereIn('TenKhungGio', [
            'Sáng 7h-9h',
            'Sáng 9h-11h',
            'Chiều 13h-15h',
            'Chiều 15h-17h',
            'Tối 18h-20h',
            'Tối 20h-22h'
        ])->get();
        // Lấy tất cả phòng học
        $phonghocs = phonghoc::all();

        // Tạo ma trận trạng thái phòng học theo khung giờ
        $matrix = [];
        foreach ($khunggios as $khunggio) {
            foreach ($phonghocs as $phong) {
                // Tìm bản ghi sử dụng phòng này ở ngày và ca này
                $ds = danhsachphong::where('TenPhong', $phong->TenPhong)
                    ->whereDate('NgaySuDung', $selectedDate->format('Y-m-d'))
                    ->where('Ca', $khunggio->TenKhungGio)
                    ->first();
                if ($ds) {
                    $matrix[$khunggio->TenKhungGio][$phong->TenPhong] = [
                        'status' => 'Đang sử dụng',
                        'MaLop' => $ds->MaLop,
                        'TrangThai' => $ds->TrangThai,
                    ];
                } else {
                    $matrix[$khunggio->TenKhungGio][$phong->TenPhong] = [
                        'status' => 'Trống',
                        'MaLop' => null,
                        'TrangThai' => null,
                    ];
                }
            }
        }
        return view('quanly_cosovatchat.phonghoc.index', compact('phonghocs', 'khunggios', 'selectedDate', 'matrix'));
    }
    public function create()
    {
        return view('quanly_cosovatchat.phonghoc.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'TenPhong' => 'required|unique:phonghoc,TenPhong',
            'LoaiPhong' => 'required',
            'SucChua' => 'nullable|integer|min:0',
            'TrangThai' => 'required|in:Trống,Đang sử dụng,Bảo trì',
        ]);
        phonghoc::create($request->all());
        return redirect()->route('phonghoc.index')->with('success', 'Thêm phòng học thành công');
    }
    public function show($tenPhong)
    {
        $phonghoc = phonghoc::findOrFail($tenPhong);
        return view('quanly_cosovatchat.phonghoc.show', compact('phonghoc'));
    }
    public function edit($tenPhong)
    {
        $phonghoc = phonghoc::where('TenPhong', $tenPhong)->firstOrFail();
        return view('quanly_cosovatchat.phonghoc.edit', compact('phonghoc'));
    }
    public function update(Request $request, $tenPhong)
    {
        $request->validate([
            'LoaiPhong' => 'required',
            'SucChua' => 'nullable|integer|min:0',
            'TrangThai' => 'required|in:Trống,Đang sử dụng,Bảo trì',
        ]);
        
        $phonghoc = phonghoc::findOrFail($tenPhong);
        $phonghoc->update($request->all());
        return redirect()->route('phonghoc.index')->with('success', 'Cập nhật phòng học thành công');
    }
    public function destroy($tenPhong)
    {
        try {
            // Xoá các bản ghi liên quan trong danhsachphong trước (nếu muốn tự động)
            // \App\Models\danhsachphong::where('TenPhong', $tenPhong)->delete();

            $phonghoc = phonghoc::findOrFail($tenPhong);
            $phonghoc->delete();

            return redirect()->route('phonghoc.index')->with('success', 'Xóa phòng học thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa phòng học: ' . $e->getMessage());
        }
    }
}