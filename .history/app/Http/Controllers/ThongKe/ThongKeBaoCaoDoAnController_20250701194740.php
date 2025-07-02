<?php

namespace App\Http\Controllers\ThongKe;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\ThongKeBaoCaoDoAn;
use App\Models\giaovien;
use App\Models\lophoc;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportsExport;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ThongKeBaoCaoDoAnController extends Controller
{
    public function showUploadForm()
    {
        return view('thong-ke.thongkebaocaodoan.upload');
    }
    public function uploadFile(Request $request)
    {
        $request->validate([
            'report_file' => 'required|mimes:doc,docx|max:10240', // Max 10MB
        ]);
        try {
            $filePath = $request->file('report_file')->getPathname();
            $phpWord = IOFactory::load($filePath);
            $class_name = '';
            $instructor_name = '';
            $reviewer_name = '';
            $found_instructor_label = false;
            $found_reviewer_label = false;
            $report_date = '';
            $report_time_range = '';
            $location = '';

            // Extract data from the Word document
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                        foreach ($element->getElements() as $textElement) {
                            if ($textElement instanceof \PhpOffice\PhpWord\Element\Text) {
                                $text = $textElement->getText();
                                if (str_contains($text, 'Lớp:')) {
                                    $class_name = trim(str_replace('Lớp:', '', $text));
                                }
                                if (str_contains($text, 'Ngày:')) {
                                    $report_date = trim(str_replace('Ngày:', '', $text));
                                }
                                if (str_contains($text, 'Giờ:')) {
                                    $report_time_range = trim(str_replace('Giờ:', '', $text));
                                }
                                if (str_contains($text, 'Địa điểm:')) {
                                    $location = trim(str_replace('Địa điểm:', '', $text));
                                }
                                // Use regex to find Vietnamese names
                                if (preg_match('/Nguyễn Việt Nga/', $text)) {
                                    $instructor_name = 'Nguyễn Việt Nga';
                                }
                                if (preg_match('/Nguyễn Trung Kiên/', $text)) {
                                    $reviewer_name = 'Nguyễn Trung Kiên';
                                }
                            }
                        }
                    } elseif ($element instanceof \PhpOffice\PhpWord\Element\Table) {
                        foreach ($element->getRows() as $row) {
                            foreach ($row->getCells() as $cell) {
                                $cellText = $cell->getText();
                                if (str_contains($cellText, 'Nguyễn Việt Nga') && str_contains($cellText, 'Giáo viên hướng dẫn')) {
                                    $instructor_name = 'Nguyễn Việt Nga';
                                }
                                if (str_contains($cellText, 'Nguyễn Trung Kiên') && str_contains($cellText, 'Giáo viên phản biện')) {
                                    $reviewer_name = 'Nguyễn Trung Kiên';
                                }
                            }
                        }
                    }
                }
            }
            // Parse date and time
            $date_parts = explode('/', $report_date);
            $report_date_formatted = Carbon::createFromDate($date_parts[2], $date_parts[1], $date_parts[0]);
            $time_parts = explode('–', $report_time_range);
            $time_start = trim($time_parts[0]);
            $time_end = trim($time_parts[1]);
            // Save data to database
            // Lấy hoặc tạo lớp học, sau đó lấy MaLop
            $class = lophoc::firstOrCreate(['TenLop' => $class_name]);
            $class_ma_lop = $class->MaLop; // Lấy MaLop

            // Lấy hoặc tạo giáo viên hướng dẫn, sau đó lấy MaGV
            $instructor = giaovien::firstOrCreate(['HoTenGV' => $instructor_name]);
            $instructor_ma_gv = $instructor->MaGV; // Lấy MaGV

            // Lấy hoặc tạo giáo viên phản biện, sau đó lấy MaGV
            $reviewer = giaovien::firstOrCreate(['HoTenGV' => $reviewer_name]);
            $reviewer_ma_gv = $reviewer->MaGV; // Lấy MaGV

            ThongKeBaoCaoDoAn::create([
                'class_id' => $class_ma_lop, // Lưu MaLop
                'instructor_id' => $instructor_ma_gv, // Lưu MaGV
                'reviewer_id' => $reviewer_ma_gv, // Lưu MaGV
                'report_date' => $report_date_formatted,
                'report_time_start' => $time_start,
                'report_time_end' => $time_end,
                'location' => $location,
                'report_name' => 'Đồ án ' . $class_name,
            ]);
            return redirect()->back()->with('success', 'File uploaded and data extracted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function showReports()
    {
        $reports = ThongKeBaoCaoDoAn::with(['class', 'instructor', 'reviewer'])->get();
        return view('thong-ke.thongkebaocaodoan.report_sumary', compact('reports'));
    }
    public function exportReports()
    {
        return Excel::download(new ReportsExport, 'thong_ke_do_an.xlsx');
    }
}
