<?php

namespace App\Imports;

use App\Models\canbo;
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
                'mahv' => 'nullable|exists:hocvi,MaHV',
                'tenchucvu' => 'nullable|exists:chucvu,TenChucVu',
                'congviecphutrach' => 'nullable',
                'madv' => 'nullable|exists:donvi,MaDV',
                'mabang' => 'nullable|exists:bangcapcanbo,MaBang',
                'mataphuan' => 'nullable|exists:taphuan,MaTapHuan',
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
                'mahv.exists' => 'Mã học vị không tồn tại trong hệ thống.',
                'tenchucvu.exists' => 'Tên chức vụ không tồn tại trong hệ thống.',
                'madv.exists' => 'Mã đơn vị không tồn tại trong hệ thống.',
                'mabang.exists' => 'Mã bằng không tồn tại trong hệ thống.',
                'mataphuan.exists' => 'Mã tập huấn không tồn tại trong hệ thống.',
            ]);

            if ($validator->fails()) {
                $errors[] = "Dòng " . ($index + 2) . ": " . implode(', ', $validator->errors()->all());
                continue;
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
        }

        // Lưu thông báo session cho controller hiển thị lại
        session()->flash('import_success_count', $successCount);
        session()->flash('import_errors', $errors);
    }
} 