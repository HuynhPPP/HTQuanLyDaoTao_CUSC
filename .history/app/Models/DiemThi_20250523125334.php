<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiemThi extends Model
{
    protected $table = 'diemthi';
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = null; // Khóa chính là composite

    protected $fillable = [
        'MaSV',
        'MaMH',
        'MaLop',
        'LanThi',
        'Diem',
        'GhiChu'
    ];

    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'MaSV', 'MaSV');
    }

    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class, 'MaMH', 'MaMH');
    }

    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'MaLop', 'MaLop');
    }
}
