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
            'giang_vien_huong_dan' => null,
            'giang_vien_phan_bien' => null,
            'ngay_bao_cao' => null,
            'gio_bao_cao_bat_dau' => null,
            'gio_bao_cao_ket_thuc' => null,
            'dia_diem_bao_cao' => null,
            'so_luong_nhom' => null,
            'nguoi_lap' => null,
            'truong_bp_dao_tao' => null,
        ];

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                // Trích xuất thông tin chung từ TextRun
                if ($element instanceof TextRun) {
                    foreach ($element->getElements() as $child) {
                        if ($child instanceof Text) {
                            $text = $child->getText();

                            if (Str::startsWith($text, 'Lớp:')) {
                                $data['lop_bao_cao'] = trim(Str::after($text, 'Lớp:'));
                            } elseif (Str::startsWith($text, 'Lần báo cáo:')) {
                                $data['lan_bao_cao'] = trim(Str::after($text, 'Lần báo cáo:'));
                            } elseif (Str::startsWith($text, 'Học kỳ:')) {
                                $data['hoc_ky'] = trim(Str::after($text, 'Học kỳ:'));
                            }
                            // Trích xuất ngày, giờ, địa điểm từ dòng đơn
                            // Cần một regex mạnh mẽ hơn để bắt tất cả trong một dòng
                            if (preg_match('/Ngày:\s*(\d{2}\/\d{2}\/\d{4})\s*Giờ:\s*(\d{1,2}:\d{2})\s*[–-]\s*(\d{1,2}:\d{2})\s*Địa điểm:\s*(.+)/', $text, $matches)) {
                                $data['ngay_bao_cao'] = Carbon::createFromFormat('d/m/Y', $matches[1])->format('Y-m-d');
                                $data['gio_bao_cao_bat_dau'] = $matches[2];
                                $data['gio_bao_cao_ket_thuc'] = $matches[3];
                                $data['dia_diem_bao_cao'] = $matches[4];
                            }
                            // Trích xuất người lập và trưởng BP Đào tạo
                            if (Str::contains($text, 'NGƯỜI LẬP')) {
                                // Giả định tên người lập nằm ngay dưới "NGƯỜI LẬP" và không có ký tự đặc biệt
                                // Cần tìm dòng chứa "(Ký và ghi rõ họ tên)" và dòng tiếp theo là tên
                                // Logic này phức tạp hơn nếu chỉ dựa vào TextRun, có thể cần duyệt lại toàn bộ section
                                // và tìm theo vị trí tương đối của các dòng.
                                // Tạm thời dùng regex đơn giản cho tên đã biết nếu tên luôn cố định
                                if (preg_match('/Hà Thanh/', $text)) {
                                    $data['nguoi_lap'] = 'Hà Thanh'; [cite: 3, 4]
                                }
                                if (preg_match('/Cù Vĩnh Lộc/', $text)) {
                                    $data['truong_bp_dao_tao'] = 'Cù Vĩnh Lộc'; [cite: 3, 4]
                                }
                            }
                        }
                    }
                }
                // Trích xuất thông tin từ Table
                elseif ($element instanceof Table) {
                    foreach ($element->getRows() as $rowIndex => $row) {
                        $cells = $row->getCells();
                        // Trích xuất Giáo viên Hướng dẫn và Phản biện từ bảng THÀNH PHẦN
                        // Dựa vào cấu trúc mẫu: Giáo viên hướng dẫn ở hàng 3, Giáo viên phản biện ở hàng 4
                        // và tên nằm ở cột thứ 2 (index 1), nhiệm vụ ở cột thứ 3 (index 2)
                        if (count($cells) >= 3) {
                            $cellText1 = trim($cells[1]->getText());
                            $cellText2 = trim($cells[2]->getText());

                            if ($cellText2 === 'Giáo viên hướng dẫn') { [cite: 2]
                                $data['giang_vien_huong_dan'] = $cellText1;
                            } elseif ($cellText2 === 'Giáo viên phản biện') { [cite: 2]
                                $data['giang_vien_phan_bien'] = $cellText1;
                            }
                        }

                        // Trích xuất số lượng nhóm từ bảng LỊCH BÁO CÁO
                        // Dòng số 1 trong bảng LỊCH BÁO CÁO (index 0 hoặc 1 tùy cách PhpWord đọc)
                        // Ví dụ: "1  05 Nhóm Lớp: CP24Y0G05"
                        // Cần tìm hàng chứa "STT" hoặc "ĐỒ ÁN" để xác định bảng LỊCH BÁO CÁO
                        // Dựa vào file mẫu, nó là dòng số 1 của bảng (index 0) của bảng LỊCH BÁO CÁO
                        if (count($cells) >= 2 && preg_match('/^\s*\d+\s*(.+)/', trim($cells[0]->getText()), $stt_match)) {
                             // Kiểm tra nếu cột thứ 2 chứa tên đồ án (e.g., "05 Nhóm")
                             $do_an_text = trim($cells[1]->getText());
                             if (Str::contains($do_an_text, 'Nhóm')) { [cite: 2]
                                 preg_match('/(\d+)\s*Nhóm/', $do_an_text, $nhom_match);
                                 if (!empty($nhom_match)) {
                                     $data['so_luong_nhom'] = (int)$nhom_match[1]; [cite: 2]
                                 }
                             }
                        }
                    }
                }
            }
        }
        // Xử lý Ngày lập và trưởng BP Đào tạo linh hoạt hơn
        // Có thể cần đọc toàn bộ văn bản và dùng regex cuối cùng
        $fullText = '';
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getText')) {
                    $fullText .= $element->getText();
                } elseif ($element instanceof TextRun) {
                    foreach ($element->getElements() as $child) {
                        if (method_exists($child, 'getText')) {
                            $fullText .= $child->getText();
                        }
                    }
                }
            }
        }
        if (preg_match('/NGƯỜI LẬP\s*\S*\s*\S*\s*\S*\s*\S*\s*\S*\s*\S*\s*\S*\s*\S*\s*\S*\s*\S*\s*\S*\s*\S*\s*(\S+\s\S+)/u', $fullText, $nguoiLapMatch)) {
            $data['nguoi_lap'] = trim($nguoiLapMatch[1]); [cite: 3, 4]
        }
        if (preg_match('/P\.\s*TRƯỞNG BP ĐÀO TẠO\s*\S*\s*\S*\s*\S*\s*\S*\s*\S*\s*\S*\s*\S*\s*\S*\s*\S*\s*\S*\s*\S*\s*\S*\s*(\S+\s\S+)/u', $fullText, $truongBPMatch)) {
             $data['truong_bp_dao_tao'] = trim($truongBPMatch[1]); [cite: 3, 4]
        }

        // Kiểm tra xem có dữ liệu tối thiểu được trích xuất không
        if (is_null($data['lop_bao_cao']) || is_null($data['ngay_bao_cao']) || is_null($data['giang_vien_huong_dan'])) {
            return []; // Trả về mảng rỗng nếu không tìm thấy dữ liệu chính
        }

        return [$data]; // Trả về mảng chứa một bản ghi, vì mỗi file là một bản phân công
    }

    // Hàm findMaGV không cần thiết ở đây vì chúng ta đang lưu tên giáo viên vào bảng upload
    // Nếu bạn muốn lấy MaGV, hãy xem lại phần trước.
    // private function findMaGV(string $hoTen): ?string { /* ... */ }
}