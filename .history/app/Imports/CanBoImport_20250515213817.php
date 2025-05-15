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
                // Tự động tạo học vị nếu không tồn tại
                if (!empty($row['mahv']) && !HocVi::where('MaHV', $row['mahv'])->exists()) {
                    HocVi::create([
                        'MaHV' => $row['mahv'],
                        'TenHocVi' => !empty($row['tenhocvi']) ? $row['tenhocvi'] : 'Học vị ' . $row['mahv'],
                    ]);
                }
                
                // Tự động tạo chức vụ nếu không tồn tại
                if (!empty($row['tenchucvu']) && !ChucVu::where('TenChucVu', $row['tenchucvu'])->exists()) {
                    ChucVu::create([
                        'TenChucVu' => $row['tenchucvu'],
                    ]);
                }
                
                // Tự động tạo đơn vị nếu không tồn tại
                if (!empty($row['madv']) && !DonVi::where('MaDV', $row['madv'])->exists()) {
                    DonVi::create([
                        'MaDV' => $row['madv'],
                        'TenDVHienTai' => !empty($row['tendvhientai']) ? $row['tendvhientai'] : 'Đơn vị ' . $row['madv'],
                    ]);
                }
                
                // Tự động tạo bằng cấp nếu không tồn tại
                if (!empty($row['mabang']) && !BangCapCanBo::where('MaBang', $row['mabang'])->exists()) {
                    BangCapCanBo::create([
                        'MaBang' => $row['mabang'],
                        'TenBang' => !empty($row['tenbang']) ? $row['tenbang'] : 'Bằng cấp ' . $row['mabang'],
                    ]);
                }
                
                // Tự động tạo tập huấn nếu không tồn tại
                if (!empty($row['mataphuan']) && !TapHuan::where('MaTapHuan', $row['mataphuan'])->exists()) {
                    TapHuan::create([
                        'MaTapHuan' => $row['mataphuan'],
                        'TenKhoaTapHuan' => !empty($row['tenkhoataphuan']) ? $row['tenkhoataphuan'] : 'Khóa tập huấn ' . $row['mataphuan'],
                    ]);
                }

                // Lưu dữ liệu nếu hợp lệ
                canbo::create([
                    'MaCB' => $row['macb'],
                    'HoTenCB' => $row['hotencb'],
                    'GioiTinh' => strtolower($row['gioitinh']) === 'nam' ? 1 : 0,
                    'Email' => $row['email'],
                    'Sdt' => $row['sdt'],
                    'MaHV' => $row['mahv'] ?? null,
                    'TenChucVu' => $row['tenchucvu'] ?? null,
                    'CongViecPhuTrach' => $row['congviecphutrach'] ?? null,
                    'MaDV' => $row['madv'] ?? null,
                    'MaBang' => $row['mabang'] ?? null,
                    'MaTapHuan' => $row['mataphuan'] ?? null,
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