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
            'report_file' => 'required|mimes:doc,docx|max:10240',
        ]);

        $file = $request->file('report_file');
        $path = $file->store('uploads/reports');
        $fullPath = storage_path('app/' . $path);

        try {
            // Kiểm tra file
            if (!file_exists($fullPath) || filesize($fullPath) === 0) {
                Storage::delete($path);
                return back()->with('error', 'Lỗi khi lưu file lên server. Vui lòng thử lại.');
            }

            \Log::info('Processing file:', [
                'original_name' => $file->getClientOriginalName(),
                'path' => $fullPath,
                'size' => filesize($fullPath)
            ]);

            $records = $parser->parse($fullPath);

            // Debug: Log số lượng record tìm được
            \Log::info('Parse results:', [
                'record_count' => count($records),
                'records' => $records
            ]);

            if (empty($records)) {
                Storage::delete($path);

                // Thông báo lỗi chi tiết hơn
                $errorMessage = 'Không tìm thấy dữ liệu trong file. ';
                $errorMessage .= 'Vui lòng kiểm tra file có chứa: ';
                $errorMessage .= '1) Thông tin lớp (ví dụ: CP24Y0G05), ';
                $errorMessage .= '2) Ngày báo cáo (dd/mm/yyyy), ';
                $errorMessage .= '3) Giờ báo cáo (hh:mm - hh:mm), ';
                $errorMessage .= '4) Địa điểm, ';
                $errorMessage .= '5) Tên giáo viên hướng dẫn và phản biện.';

                return back()->with('error', $errorMessage);
            }

            // Kiểm tra dữ liệu có đầy đủ không
            $incompleteRecords = array_filter($records, function ($record) {
                return empty($record['class_id']) ||
                    empty($record['report_date']) ||
                    empty($record['report_time_start']) ||
                    empty($record['report_time_end']) ||
                    empty($record['location']);
            });

            if (!empty($incompleteRecords)) {
                \Log::warning('Incomplete records found:', $incompleteRecords);

                Storage::delete($path);
                return back()->with('error', 'Một số thông tin bắt buộc bị thiếu trong file. Vui lòng kiểm tra lại định dạng.');
            }

            // Lưu dữ liệu
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

            return redirect()->route('bao-cao.index')
                ->with('success', "Tải file thành công! Đã tạo {count($records)} record báo cáo.");

        } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
            Storage::delete($path);
            \Log::error('PhpWord Error: ' . $e->getMessage(), [
                'file' => $file->getClientOriginalName(),
                'exception' => $e
            ]);

            return back()->with('error', 'Lỗi khi xử lý file Word. File có thể bị lỗi hoặc không đúng định dạng. Vui lòng thử lưu lại file dưới dạng .docx chuẩn và upload lại.');

        } catch (\Exception $e) {
            Storage::delete($path);
            \Log::error('Upload Error: ' . $e->getMessage(), [
                'file' => $file->getClientOriginalName(),
                'exception' => $e
            ]);

            return back()->with('error', 'Đã có lỗi xảy ra: ' . $e->getMessage());
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
