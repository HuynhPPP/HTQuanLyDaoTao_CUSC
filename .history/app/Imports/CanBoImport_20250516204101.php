<?php

namespace App\Imports;

use App\Models\canbo;
use App\Models\hocvi;
use App\Models\bangcapcanbo;
use App\Models\chucvu;
use App\Models\donvi;
use App\Models\TapHuan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class CanBoImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $errors = [];
        $successCount = 0;

        foreach ($rows as $index => $row) {
            if ($row->filter()->isEmpty())
                continue; // Bỏ qua dòng trống

            $validator = Validator::make($row->toArray(), [
                'macb' => 'required|unique:canbo,MaCB',
                'hotencb' => 'required|string',
                'gioitinh' => 'required|in:Nam,Nữ',
                'email' => 'required|email|unique:canbo,Email',
                'sdt' => 'required',
                'mahv' => 'nullable',
                'tenchucvu' => 'nullable',
                'congviecphutrach' => 'nullable',
                'madv' => 'nullable',
                'mabang' => 'nullable',
                'mataphuan' => 'nullable',
                'thoigianbdcongtaccusc' => [
                    'nullable',
                    function ($attribute, $value, $fail) {
                        if ($value) {
                            try {
                                Carbon::parse($value);
                            } catch (\Exception $e) {
                                $fail('Thời gian bắt đầu không hợp lệ. Định dạng nên là YYYY-MM-DD.');
                            }
                        }
                    }
                ],
                'thoigianktcongtaccusc' => [
                    'nullable',
                    function ($attribute, $value, $fail) {
                        if ($value) {
                            try {
                                Carbon::parse($value);
                            } catch (\Exception $e) {
                                $fail('Thời gian kết thúc không hợp lệ. Định dạng nên là YYYY-MM-DD.');
                            }
                        }
                    }
                ],
            ], [
                'macb.required' => 'Mã cán bộ là bắt buộc.',
                'macb.unique' => 'Mã cán bộ đã tồn tại.',
                'hotencb.required' => 'Họ tên cán bộ là bắt buộc.',
                'gioitinh.required' => 'Giới tính là bắt buộc.',
                'gioitinh.in' => 'Giới tính phải là Nam hoặc Nữ.',
                'email.required' => 'Email là bắt buộc.',
                'email.email' => 'Email không đúng định dạng.',
                'email.unique' => 'Email đã tồn tại.',
                'sdt.required' => 'Số điện thoại là bắt buộc.',
            ]);

            if ($validator->fails()) {
                $errors[] = "Dòng " . ($index + 2) . ": " . implode(', ', $validator->errors()->all());
                continue;
            }

            try {
                // Kiểm tra và xử lý từng khóa ngoại trước khi import

                // Xử lý Học Vị
                $maHV = null;
                if (!empty($row['mahv'])) {
                    $hocVi = HocVi::firstOrCreate(
                        ['MaHV' => $row['mahv']],
                        ['TenHocVi' => $row['tenhocvi'] ?? 'Học vị ' . $row['mahv']]
                    );
                    $maHV = $hocVi->MaHV;
                }

                // Xử lý Chức Vụ
                $tenChucVu = null;
                if (!empty($row['tenchucvu'])) {
                    $chucVu = ChucVu::firstOrCreate(
                        ['TenChucVu' => $row['tenchucvu']]
                    );
                    $tenChucVu = $chucVu->TenChucVu;
                }

                // Xử lý Đơn Vị
                $maDV = null;
                if (!empty($row['madv'])) {
                    $donVi = DonVi::firstOrCreate(
                        ['MaDV' => $row['madv']],
                        ['TenDVHienTai' => $row['tendvhientai'] ?? 'Đơn vị ' . $row['madv']]
                    );
                    $maDV = $donVi->MaDV;
                }

                // Xử lý Bằng Cấp
                $maBang = null;
                if (!empty($row['mabang'])) {
                    $bangCap = BangCapCanBo::firstOrCreate(
                        ['MaBang' => $row['mabang']],
                        ['TenBang' => $row['tenbang'] ?? 'Bằng cấp ' . $row['mabang']]
                    );
                    $maBang = $bangCap->MaBang;
                }

                // Xử lý Tập Huấn
                $maTapHuan = null;
                if (!empty($row['mataphuan'])) {
                    $tapHuan = TapHuan::firstOrCreate(
                        ['MaTapHuan' => $row['mataphuan']],
                        ['TenKhoaTapHuan' => $row['tenkhoataphuan'] ?? 'Khóa tập huấn ' . $row['mataphuan']]
                    );
                    $maTapHuan = $tapHuan->MaTapHuan;
                }

                // Xử lý Công Việc Phụ Trách
                $congViecPhuTrach = null;
                if (!empty($row['congviecphutrach'])) {
                    $phuTrach = PhuTrach::firstOrCreate(
                        ['CongViecPhuTrach' => $row['congviecphutrach']],
                        ['MieuTaChiTiet' => $row['mieutachitiet'] ?? 'Mô tả công việc ' . $row['congviecphutrach']]
                    );
                    $congViecPhuTrach = $phuTrach->CongViecPhuTrach;
                }

                // Lưu dữ liệu Cán Bộ
                $canBo = CanBo::create([
                    'MaCB' => $row['macb'],
                    'HoTenCB' => $row['hotencb'],
                    'GioiTinh' => strtolower($row['gioitinh']) === 'nam' ? 1 : 0,
                    'Email' => $row['email'],
                    'Sdt' => $row['sdt'],
                    'MaHV' => $maHV,
                    'TenChucVu' => $tenChucVu,
                    'CongViecPhuTrach' => $congViecPhuTrach,
                    'MaDV' => $maDV,
                    'MaBang' => $maBang,
                    'MaTapHuan' => $maTapHuan,
                    'ThoiGianBDCongTacCUSC' => $row['thoigianbdcongtaccusc'] ? Carbon::parse($row['thoigianbdcongtaccusc'])->format('Y-m-d') : null,
                    'ThoiGianKTCongTacCUSC' => $row['thoigianktcongtaccusc'] ? Carbon::parse($row['thoigianktcongtaccusc'])->format('Y-m-d') : null,
                ]);

                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Dòng " . ($index + 2) . ": Lỗi - " . $e->getMessage();
            }
        }

        // Lưu thông báo session cho controller hiển thị lại
        session()->flash('import_success_count', $successCount);
        session()->flash('import_errors', $errors);
    }
}