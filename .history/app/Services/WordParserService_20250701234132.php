<?php

namespace App\Services;

use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PhpOffice\PhpWord\Exception\Exception as PhpWordException;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Row;
use PhpOffice\PhpWord\Element\Cell;
use PhpOffice\PhpWord\Element\AbstractElement; // Import this

class WordParserService
{
    /**
     * Extracts all text content from a PhpWord element recursively.
     * This is a helper to get a "flat" text representation of the document.
     * @param AbstractElement[] $elements
     * @return string
     */
    private function getTextFromElements(array $elements): string
    {
        $fullText = '';
        foreach ($elements as $element) {
            if ($element instanceof Text) {
                $fullText .= $element->getText();
            } elseif ($element instanceof TextRun) {
                $fullText .= $this->getTextFromElements($element->getElements());
            } elseif ($element instanceof Table) {
                foreach ($element->getRows() as $row) {
                    foreach ($row->getCells() as $cell) {
                        $fullText .= $this->getTextFromElements($cell->getElements());
                        $fullText .= "\t"; // Use tab to separate cell contents
                    }
                    $fullText .= "\n"; // Newline after each row
                }
            }
            // Add other element types if needed, e.g., if ($element instanceof Paragraph)
            // For general paragraphs, they often contain TextRuns or Text directly.
            // If the element itself can represent text, you might try:
            // elseif (method_exists($element, 'getText')) {
            //    $fullText .= $element->getText();
            // }
            $fullText .= "\n"; // Add a newline after each major block (paragraph, table)
        }
        return $fullText;
    }

    public function parse(string $filePath): array
    {
        try {
            $phpWord = IOFactory::load($filePath);
        } catch (PhpWordException $e) {
            throw new \Exception("Không thể đọc file Word. File có thể bị hỏng, có mật khẩu hoặc không tương thích. Lỗi gốc: " . $e->getMessage(), $e->getCode(), $e);
        }

        $data = [
            'lop_bao_cao' => null,
            'lan_bao_cao' => null,
            'hoc_ky' => null,
            'giang_vien_huong_dan' => null,
            'giang_vien_phan_bien' => null,
            'ngay_bao_cao' => null,
            'gio_bao_cao_bat_dau' => null,
            'gio_bao_cao_ket_thuc' => null,
            'dia_diem_bao_cao' => null,
            'so_luong_nhom' => null,
        ];

        // Lấy toàn bộ văn bản của tài liệu
        $fullDocumentText = '';
        foreach ($phpWord->getSections() as $section) {
            $fullDocumentText .= $this->getTextFromElements($section->getElements());
        }

        // --- Bắt đầu trích xuất bằng Regex trên toàn bộ văn bản ---

        // Trích xuất Lớp
        if (preg_match('/Lớp:\s*([^\n]+)/u', $fullDocumentText, $matches)) {
            $data['lop_bao_cao'] = trim($matches[1]);
        }

        // Trích xuất Lần báo cáo
        if (preg_match('/Lần báo cáo:\s*([^\n]+)/u', $fullDocumentText, $matches)) {
            $data['lan_bao_cao'] = trim($matches[1]);
        }

        // Trích xuất Học kỳ
        if (preg_match('/Học kỳ:\s*([^\n]+)/u', $fullDocumentText, $matches)) {
            $data['hoc_ky'] = trim($matches[1]);
        }

        // Trích xuất Ngày, Giờ, Địa điểm
        // Regex này cần rất mạnh mẽ để xử lý các khoảng trắng, xuống dòng, tab
        if (preg_match('/Ngày:\s*(\d{2}\/\d{2}\/\d{4})\s*Giờ:\s*(\d{1,2}:\d{2})\s*[–-]\s*(\d{1,2}:\d{2})\s*Địa điểm:\s*(.+)/u', $fullDocumentText, $matches)) {
            $data['ngay_bao_cao'] = Carbon::createFromFormat('d/m/Y', $matches[1])->format('Y-m-d');
            $data['gio_bao_cao_bat_dau'] = $matches[2];
            $data['gio_bao_cao_ket_thuc'] = $matches[3];
            $data['dia_diem_bao_cao'] = trim($matches[4]);
        } else {
            // Trường hợp Ngày/Giờ/Địa điểm bị ngắt dòng hoặc phân tách khác
            // Cần thêm logic nếu format này không cố định trên một dòng
        }

        // Trích xuất Giảng viên hướng dẫn và Giảng viên phản biện từ bảng THÀNH PHẦN
        // Đây là phần khó nhất với cách tiếp cận toàn văn bản vì bảng có cấu trúc
        // Chúng ta cần một regex có khả năng nắm bắt ngữ cảnh bảng
        // Ví dụ: tìm "HỌ TÊN\s*NHIỆM VỤ" và sau đó tìm các dòng dữ liệu
        if (preg_match_all('/(HỌ TÊN\s*NHIỆM VỤ.*?)(\n.+)+?(\nSTT\s*ĐỒ ÁN|\nNgày \d{2} tháng \d{2} năm \d{4}|\n\n)/us', $fullDocumentText, $tableMatches)) {
            foreach ($tableMatches[1] as $tableContent) {
                if (Str::contains($tableContent, 'Giáo viên hướng dẫn') && Str::contains($tableContent, 'Giáo viên phản biện')) {
                    // Cố gắng trích xuất từ phần nội dung bảng này
                    if (preg_match('/(.*?)\s*Giáo viên hướng dẫn/u', $tableContent, $gvhdMatch)) {
                        $gvhd_lines = explode("\n", trim($gvhdMatch[1]));
                        $data['giang_vien_huong_dan'] = end($gvhd_lines); // Lấy dòng cuối cùng trước "Giáo viên hướng dẫn"
                    }
                    if (preg_match('/(.*?)\s*Giáo viên phản biện/u', $tableContent, $gvpbMatch)) {
                        $gvpb_lines = explode("\n", trim($gvpbMatch[1]));
                        $data['giang_vien_phan_bien'] = end($gvpb_lines); // Lấy dòng cuối cùng trước "Giáo viên phản biện"
                    }
                    break; // Chỉ lấy từ bảng thành phần đầu tiên tìm thấy
                }
            }
        }
        
        // Trích xuất Số lượng nhóm (từ bảng LỊCH BÁO CÁO)
        // Tìm dòng chứa "ĐỒ ÁN" và sau đó dòng chứa số lượng nhóm
        if (preg_match('/ĐỒ ÁN\s*GIÁO VIÊN\s*HƯỚNG DẪN\s*GIÁO VIÊN\s*PHẢN BIỆN\s*\n\s*\d+\s*(\d+)\s*Nhóm/u', $fullDocumentText, $matches)) {
            $data['so_luong_nhom'] = (int)$matches[1];
        }


        // Kiểm tra xem có dữ liệu tối thiểu được trích xuất không
        if (is_null($data['lop_bao_cao']) || is_null($data['ngay_bao_cao']) || is_null($data['giang_vien_huong_dan'])) {
            return []; // Trả về mảng rỗng nếu không tìm thấy dữ liệu chính
        }

        return [$data]; // Trả về mảng chứa một bản ghi
    }
}