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

class WordParserService
{
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
            'giang_vien_huong_dan' => null, // Tên GVHD chính từ bảng Thành phần
            'giang_vien_phan_bien' => null, // Tên GVPB chính từ bảng Thành phần
            'ngay_bao_cao' => null,
            'gio_bao_cao_bat_dau' => null,
            'gio_bao_cao_ket_thuc' => null,
            'dia_diem_bao_cao' => null,
            'so_luong_nhom' => null, // Ví dụ: 5
            // Các trường người lập và trưởng BP đã bị loại bỏ theo yêu cầu mới
            'ten_do_an_tu_bang' => null, // Để lưu tên đồ án nếu có nhiều nhóm (không lưu vào DB trực tiếp)
        ];

        // Không còn cần $fullDocumentText nếu không trích xuất Người lập / Trưởng BP

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                // Xử lý TextRun (chứa thông tin Lớp, Lần báo cáo, Học kỳ, Ngày/Giờ/Địa điểm)
                if ($element instanceof TextRun) {
                    $textRunContent = '';
                    foreach ($element->getElements() as $child) {
                        if ($child instanceof Text) {
                            $textRunContent .= $child->getText();
                        }
                    }

                    // Trích xuất thông tin chung từ TextRun
                    if (Str::startsWith($textRunContent, 'Lớp:')) {
                        $data['lop_bao_cao'] = trim(Str::after($textRunContent, 'Lớp:'));
                    } elseif (Str::startsWith($textRunContent, 'Lần báo cáo:')) {
                        $data['lan_bao_cao'] = trim(Str::after($textRunContent, 'Lần báo cáo:'));
                    } elseif (Str::startsWith($textRunContent, 'Học kỳ:')) {
                        $data['hoc_ky'] = trim(Str::after($textRunContent, 'Học kỳ:'));
                    }
                    // Trích xuất Ngày, Giờ, Địa điểm từ một dòng cụ thể
                    if (preg_match('/Ngày:\s*(\d{2}\/\d{2}\/\d{4})\s*Giờ:\s*(\d{1,2}:\d{2})\s*[–-]\s*(\d{1,2}:\d{2})\s*Địa điểm:\s*(.+)/u', $textRunContent, $matches)) {
                        $data['ngay_bao_cao'] = Carbon::createFromFormat('d/m/Y', $matches[1])->format('Y-m-d');
                        $data['gio_bao_cao_bat_dau'] = $matches[2];
                        $data['gio_bao_cao_ket_thuc'] = $matches[3];
                        $data['dia_diem_bao_cao'] = $matches[4];
                    }
                }
                // Xử lý Table (chứa thông tin Giảng viên hướng dẫn/phản biện và Số lượng nhóm)
                elseif ($element instanceof Table) {
                    $tableContent = []; // Tạm lưu nội dung bảng
                    foreach ($element->getRows() as $rowIndex => $row) {
                        $rowData = [];
                        foreach ($row->getCells() as $cell) {
                            $cellText = '';
                            foreach ($cell->getElements() as $cellElement) {
                                if ($cellElement instanceof TextRun) {
                                    foreach ($cellElement->getElements() as $cellChild) {
                                        if ($cellChild instanceof Text) {
                                            $cellText .= $cellChild->getText();
                                        }
                                    }
                                } elseif ($cellElement instanceof Text) {
                                    $cellText .= $cellElement->getText();
                                }
                            }
                            $rowData[] = trim($cellText);
                        }
                        $tableContent[] = $rowData;
                    }

                    // Tìm và trích xuất thông tin từ bảng "THÀNH PHẦN"
                    if (
                        !empty($tableContent) && (
                            (isset($tableContent[0][0]) && Str::contains($tableContent[0][0], 'STT')) &&
                            (isset($tableContent[0][1]) && Str::contains($tableContent[0][1], 'HỌ TÊN')) &&
                            (isset($tableContent[0][2]) && Str::contains($tableContent[0][2], 'NHIỆM VỤ'))
                        )
                    ) {
                        foreach ($tableContent as $row) {
                            if (isset($row[2])) { // Cột nhiệm vụ
                                if ($row[2] === 'Giáo viên hướng dẫn' && isset($row[1])) {
                                    $data['giang_vien_huong_dan'] = $row[1];
                                } elseif ($row[2] === 'Giáo viên phản biện' && isset($row[1])) {
                                    $data['giang_vien_phan_bien'] = $row[1];
                                }
                            }
                        }
                    }

                    // Tìm và trích xuất thông tin từ bảng "LỊCH BÁO CÁO"
                    if (
                        !empty($tableContent) && (
                            (isset($tableContent[0][0]) && Str::contains($tableContent[0][0], 'STT')) &&
                            (isset($tableContent[0][1]) && Str::contains($tableContent[0][1], 'ĐỒ ÁN')) &&
                            (isset($tableContent[0][2]) && Str::contains($tableContent[0][2], 'GIÁO VIÊN'))
                        )
                    ) {
                        foreach ($tableContent as $row) {
                            // Kiểm tra cột STT để xác định hàng dữ liệu
                            if (isset($row[0]) && preg_match('/^\d+$/', $row[0])) {
                                if (isset($row[1])) { // Cột ĐỒ ÁN
                                    $doAnText = $row[1];
                                    if (preg_match('/^(\d+)\s*Nhóm/', $doAnText, $nhomMatch)) {
                                        $data['so_luong_nhom'] = (int) $nhomMatch[1];
                                    }
                                    $data['ten_do_an_tu_bang'] = $doAnText;
                                }
                            }
                        }
                    }
                }
            }
        }

        // Không còn xử lý trích xuất Người lập và P. Trưởng BP Đào tạo

        // Kiểm tra xem có dữ liệu tối thiểu được trích xuất không
        if (is_null($data['lop_bao_cao']) || is_null($data['ngay_bao_cao']) || is_null($data['giang_vien_huong_dan'])) {
            // Nếu không tìm thấy đủ thông tin cơ bản, coi như file không đúng định dạng
            return [];
        }

        // Trả về dữ liệu dưới dạng một mảng chứa một bản ghi
        return [$data];
    }
}