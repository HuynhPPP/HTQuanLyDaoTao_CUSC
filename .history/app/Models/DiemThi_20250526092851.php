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
    public static function createOrUpdateDiem($maSV, $tenMH, $maLop, $lanThi, $diem, $ghiChu = null)
    {
        return static::updateOrCreate(
            [
                'MaSV' => $maSV,
                'TenMH' => $tenMH,
                'LanThi' => $lanThi
            ],
            [
                'MaLop' => $maLop,
                'Diem' => (float) $diem,
                'GhiChu' => $ghiChu
            ]
        );
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