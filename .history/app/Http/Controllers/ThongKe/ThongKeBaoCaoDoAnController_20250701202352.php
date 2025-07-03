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
    public function upload(Request $request, WordParserService $parser)
    {
        $request->validate([
            'report_file' => 'required|mimes:doc,docx|max:10240',
        ]);

        $file = $request->file('report_file');
        $path = $file->store('uploads/reports');

        try {
            $records = $parser->parse(storage_path('app/' . $path));

            foreach ($records as $record) {
                ThongKeBaoCaoDoAn::create($record);
            }

            BaoCaoDoAnUpload::create([
                'ten_file' => $file->getClientOriginalName(),
                'duong_dan' => $path,
                'lop' => $records[0]['class_id'] ?? 'N/A',
                'dot_bao_cao' => null,
                'nguoi_lap' => auth()->user()->name ?? null,
                'ngay_lap' => now(),
            ]);

            return redirect()->route('bao-cao.index')->with('success', 'Tải file & tổng hợp thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi khi xử lý file: ' . $e->getMessage());
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
        return view('bao-cao.edit', compact('report', 'giaoViens'));
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
