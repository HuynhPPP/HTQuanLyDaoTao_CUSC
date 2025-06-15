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
        $query = phonghoc::query();

        // Determine the selected date and time slot
        $selectedDate = $request->input('ngay') ? Carbon::parse($request->input('ngay')) : Carbon::now();
        $selectedKhungGioTen = $request->input('khung_gio'); // Tên khung giờ được chọn từ bộ lọc
        $selectedKhungGioMaCa = null;

        // Đảm bảo các khung giờ mặc định luôn tồn tại
        $defaultTimeSlots = [
            ['TenKhungGio' => 'Sáng', 'ThoiGian' => '08:00 - 11:00'],
            ['TenKhungGio' => 'Chiều', 'ThoiGian' => '13:00 - 16:00'],
            ['TenKhungGio' => 'Tối', 'ThoiGian' => '18:00 - 21:00']
        ];

        foreach ($defaultTimeSlots as $slot) {
            khunggio::firstOrCreate(
                ['TenKhungGio' => $slot['TenKhungGio']],
                ['ThoiGian' => $slot['ThoiGian']]
            );
        }

        if ($selectedKhungGioTen) {
            $khungGioObj = khunggio::where('TenKhungGio', $selectedKhungGioTen)->first();
            if ($khungGioObj) {
                $selectedKhungGioMaCa = $khungGioObj->TenKhungGio;
            }
        }

        // Build the query for phonghocs, eagerly loading danhsachphong with relevant filters
        $phonghocs = $query->with(['danhsachphong' => function($q) use ($selectedDate, $selectedKhungGioMaCa) {
            $q->whereDate('NgaySuDung', $selectedDate->format('Y-m-d'));
            if ($selectedKhungGioMaCa) {
                $q->where('Ca', $selectedKhungGioMaCa);
            }
            // Eager load nested relationships for danhsachphong
            $q->with(['lopHoc.tkb.hocki.danhsachmonhoc.monhoc']);
        }])
        ->when($request->has('trang_thai'), function ($q) use ($request) {
            $q->where('TrangThai', $request->trang_thai);
        })
        ->paginate(10);

        // Get all time slots (for the dropdown)
        $khunggios = khunggio::whereIn('TenKhungGio', ['Sáng', 'Chiều', 'Tối'])->get();

        return view('quanly_cosovatchat.phonghoc.index', compact('phonghocs', 'khunggios', 'selectedDate', 'selectedKhungGioTen'));
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