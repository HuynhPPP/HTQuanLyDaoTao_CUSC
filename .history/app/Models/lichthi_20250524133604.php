<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichThi extends Model
{
    protected $table = 'lichthi';
    protected $primaryKey = 'MaLichThi';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'MaLop',
        'TenMH',
        'NgayThi',
        'KhungGio',
        'PhongThi',
        'LoaiThi',
        'GhiChu'
    ];

    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class, 'TenMH', 'TenMH');
    }

    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'MaLop', 'MaLop');
    }

    public function phanCongThi()
    {
        return $this->hasMany(PhieuPhanCongThi::class, 'MaLichThi', 'MaLichThi');
    }
}
