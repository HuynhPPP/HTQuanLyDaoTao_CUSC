<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiemThi extends Model
{
    protected $table = 'diemthi';
    public $timestamps = true;
    public $incrementing = false;
    
    // Định nghĩa composite primary key
    protected $primaryKey = ['MaSV', 'TenMH', 'LanThi'];
    protected $keyType = 'string';

    protected $fillable = [
        'MaSV',
        'TenMH',
        'MaLop',
        'LanThi',
        'Diem',
        'GhiChu'
    ];

    // Override các method để xử lý composite key
    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    protected function getKeyForSaveQuery($keyName = null)
    {
        if (is_null($keyName)) {
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }

    // Tìm record bằng composite key
    public static function findByCompositeKey($maSV, $tenMH, $lanThi)
    {
        return static::where([
            ['MaSV', '=', $maSV],
            ['TenMH', '=', $tenMH],
            ['LanThi', '=', $lanThi]
        ])->first();
    }

    // Tạo hoặc cập nhật record
    public static function createOrUpdateDiem($maSV, $tenMH, $maLop, $lanThi, $diemLyThuyet, $diemThucHanh, $diemDuAn, $ghiChu = null)
    {
        // Tính điểm tổng kết theo trọng số
        $diemTongKet = 
            ($diemLyThuyet * 0.5) +  // 50% điểm lý thuyết 
            ($diemThucHanh * 0.3) +  // 30% điểm thực hành
            ($diemDuAn * 0.2);       // 20% điểm dự án

        // Xác định trạng thái đạt chuẩn (ví dụ: >= 5.0)
        $trangThai = $diemTongKet >= 5.0 ? 'DatChuan' : 'ChuaDatChuan';

        return self::updateOrCreate(
            [
                'MaSV' => $maSV,
                'TenMH' => $tenMH,
                'MaLop' => $maLop,
                'LanThi' => $lanThi
            ],
            [
                'DiemLyThuyet' => $diemLyThuyet,
                'DiemThucHanh' => $diemThucHanh,
                'DiemDuAn' => $diemDuAn,
                'DiemTongKet' => $diemTongKet,
                'TrangThai' => $trangThai,
                'GhiChu' => $ghiChu
            ]
        );
    }

    // Phương thức để lấy chi tiết điểm của sinh viên
    public function getChiTietDiem()
    {
        return [
            'LyThuyet' => [
                'Diem' => $this->DiemLyThuyet,
                'TrongSo' => 0.5,
                'SoBaiThi' => 6,
                'ThoiGianThi' => 40
            ],
            'ThucHanh' => [
                'Diem' => $this->DiemThucHanh,
                'TrongSo' => 0.3,
                'SoBaiThi' => 5,
                'ThoiGianThi' => 60
            ],
            'DuAn' => [
                'Diem' => $this->DiemDuAn,
                'TrongSo' => 0.2,
                'ThoiGianThucHien' => 24
            ],
            'TongKet' => [
                'Diem' => $this->DiemTongKet,
                'TrangThai' => $this->TrangThai
            ]
        ];
    }

    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'MaSV', 'MaSV');
    }

    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class, 'TenMH', 'TenMH');
    }

    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'MaLop', 'MaLop');
    }
}