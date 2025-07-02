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
use App\Services\ReportParserService;
use App\Services\ReportSummaryService;
use App\Exports\ReportSummaryExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ThongKeBaoCaoDoAnController extends Controller
{
    public function uploadForm()
    {
        return view('thong-ke.thongkebaocaodoan.upload');
    }
    // App\Http\Controllers\ThongKe\ThongKeBaoCaoDoAnController.php

    public function handleUpload(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:pdf,jpg,jpeg,png',
        ]);

        $parser = new ReportParserService();
        $summaryService = new ReportSummaryService();

        $parsedData = [];
        foreach ($request->file('files') as $file) {
            $parsed = $parser->parse($file->getRealPath());
            $parsedData = array_merge($parsedData, $parsed);
        }

        $summary = $summaryService->summarize($parsedData);

        return Excel::download(new ReportSummaryExport($summary), 'thong_ke_cham_bao_cao.xlsx');
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
