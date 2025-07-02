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
use PhpOffice\PhpWord\Exception\Exception as PhpWordException;

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
            'report_file' => 'required|mimes:doc,docx|max:10240',
        ]);

        $file = $request->file('report_file');
        $path = $file->store('uploads/reports');
        $fullPath = storage_path('app/' . $path);

        try {
            // Kiểm tra xem file có thực sự tồn tại và có nội dung không
            if (!file_exists($fullPath) || filesize($fullPath) === 0) {
                // Xóa file tạm nếu có lỗi
                Storage::delete($path);
                return back()->with('error', 'Lỗi khi lưu file lên server. Vui lòng thử lại.');
            }

            $records = $parser->parse($fullPath);

            // Nếu parse không ra được record nào, có thể định dạng file sai
            if (empty($records)) {
                Storage::delete($path); // Xóa file tạm
                return back()->with('error', 'Không tìm thấy dữ liệu trong file. Vui lòng kiểm tra lại định dạng file Word theo mẫu.');
            }

            foreach ($records as $record) {
                ThongKeBaoCaoDoAn::create($record);
            }

            BaoCaoDoAnUpload::create([
                'ten_file' => $file->getClientOriginalName(),
                'duong_dan' => $path,
                // Sửa lại logic lấy lớp và các thông tin khác nếu cần
                'lop' => $records[0]['class_id'] ?? 'N/A',
                'dot_bao_cao' => null,
                'nguoi_lap' => auth()->user()->name ?? null,
                'ngay_lap' => now(),
            ]);

            return redirect()->route('bao-cao.index')->with('success', 'Tải file & tổng hợp thành công!');
        } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
            // Bắt lỗi cụ thể từ PhpWord
            Storage::delete($path); // Xóa file tạm
            \Log::error('Loi PhpWord: ' . $e->getMessage()); // Ghi log để debug
            return back()->with('error', 'Lỗi khi xử lý file Word. File có thể bị lỗi hoặc không đúng định dạng. Vui lòng thử lưu lại file dưới dạng .docx chuẩn và upload lại.');
        } catch (\Exception $e) {
            // Bắt các lỗi chung khác
            Storage::delete($path); // Xóa file tạm
            \Log::error('Loi Upload: ' . $e->getMessage()); // Ghi log để debug
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
}
