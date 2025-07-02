<?php

namespace App\Http\Controllers\ThongKe;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\ThongKeBaoCaoDoAn;
use App\Models\giaovien;
use App\Models\lophoc;
use App\Models\BaoCaoDoAnUpload;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
use App\Exports\ReportsExport;
use Illuminate\Support\Facades\Storage;
use App\Services\WordParserService;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ThongKeBaoCaoDoAnController extends Controller
{
    public function uploadForm()
    {
        return view('thong-ke.thongkebaocaodoan.upload');
    }
    // App\Http\Controllers\ThongKe\ThongKeBaoCaoDoAnController.php

    public function upload(Request $request, WordParserService $parser)
    {
        $request->validate([
            'report_file' => 'required|mimes:doc,docx',
        ]);

        $file = $request->file('report_file');
        $path = $file->store('uploads/reports');
        $fullPath = storage_path('app/' . $path);

        try {
            if (!file_exists($fullPath) || filesize($fullPath) === 0) {
                Storage::delete($path);
                return back()->with('error', 'Lỗi khi lưu file lên server hoặc file rỗng. Vui lòng thử lại.');
            }

            $records = $parser->parse($fullPath);
            // dd($records);

            if (empty($records)) {
                Storage::delete($path);
                return back()->with('error', 'Không tìm thấy dữ liệu trong file. Vui lòng kiểm tra lại định dạng file Word theo mẫu.');
            }

            // Mảng $records sẽ chỉ chứa 1 phần tử (là $data từ service)
            $reportData = $records[0];

            BaoCaoDoAnUpload::create([
                'ten_file' => $file->getClientOriginalName(),
                'duong_dan' => $path,
                'lop_bao_cao' => $reportData['lop_bao_cao'],
                'lan_bao_cao' => $reportData['lan_bao_cao'],
                'hoc_ky' => $reportData['hoc_ky'],
                'giang_vien_huong_dan' => $reportData['giang_vien_huong_dan'],
                'giang_vien_phan_bien' => $reportData['giang_vien_phan_bien'],
                'ngay_bao_cao' => $reportData['ngay_bao_cao'],
                'gio_bao_cao_bat_dau' => $reportData['gio_bao_cao_bat_dau'],
                'gio_bao_cao_ket_thuc' => $reportData['gio_bao_cao_ket_thuc'],
                'dia_diem_bao_cao' => $reportData['dia_diem_bao_cao'],
                'so_luong_nhom' => $reportData['so_luong_nhom'],
                'nguoi_lap' => $reportData['nguoi_lap'] ?? (auth()->check() ? auth()->user()->name : null), // Lấy từ file hoặc user đăng nhập
                'truong_bp_dao_tao' => $reportData['truong_bp_dao_tao'],
                // `ngay_lap` có thể lấy từ $reportData['ngay_lap'] nếu nó là ngày lập file,
                // hoặc `now()` nếu là ngày upload. Tôi để `now()` như bạn đang dùng.
                'ngay_lap' => now(),
            ]);

            return redirect()->route('bao-cao.index')->with('success', 'Tải file & tổng hợp thành công!');
        } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
            Storage::delete($path);
            \Log::error('Loi PhpWord khi upload file ' . $file->getClientOriginalName() . ': ' . $e->getMessage());
            return back()->with('error', 'Lỗi khi xử lý file Word. File có thể bị lỗi hoặc không đúng định dạng. Vui lòng thử lưu lại file dưới dạng .docx chuẩn và upload lại.');
        } catch (\Exception $e) {
            Storage::delete($path);
            \Log::error('Loi chung khi upload file ' . $file->getClientOriginalName() . ': ' . $e->getMessage());
            return back()->with('error', 'Đã có lỗi xảy ra trong quá trình xử lý: ' . $e->getMessage());
        }
    }
    public function index()
    {
        $reports = ThongKeBaoCaoDoAn::with(['instructor', 'reviewer'])->orderBy('report_date')->get();
        return view('thong-ke.thongkebaocaodoan.index', compact('reports'));
    }
    public function edit($id)
    {
        $report = ThongKeBaoCaoDoAn::findOrFail($id);
        $giaoViens = GiaoVien::all();
        return view('thong-ke.thongkebaocaodoan.edit', compact('report', 'giaoViens'));
    }
    public function update(Request $request, $id)
    {
        $report = ThongKeBaoCaoDoAn::findOrFail($id);

        $report->update($request->only([
            'report_name',
            'instructor_id',
            'reviewer_id',
            'report_date',
            'report_time_start',
            'report_time_end',
            'location'
        ]));

        return redirect()->route('bao-cao.index')->with('success', 'Cập nhật thành công!');
    }
    public function export()
    {
        $reports = ThongKeBaoCaoDoAn::with(['instructor', 'reviewer'])->orderBy('class_id')->get();

        return Excel::download(new \App\Exports\ThongKeDoAnExport($reports), 'thong-ke-bao-cao-do-an.xlsx');
    }

    // Thêm method này vào Controller để test
    public function debugParse(Request $request, WordParserService $parser)
    {
        $request->validate([
            'report_file' => 'required|mimes:doc,docx|max:10240',
        ]);

        $file = $request->file('report_file');
        $path = $file->store('uploads/reports');
        $fullPath = storage_path('app/' . $path);

        try {
            $records = $parser->parse($fullPath);

            // Xóa file tạm
            Storage::delete($path);

            // Trả về JSON để debug
            return response()->json([
                'success' => true,
                'records_found' => count($records),
                'records' => $records,
                'message' => 'Debug thành công! Kiểm tra log để xem chi tiết.'
            ]);

        } catch (\Exception $e) {
            Storage::delete($path);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Lỗi khi parse file. Kiểm tra log để xem chi tiết.'
            ]);
        }
    }
}
