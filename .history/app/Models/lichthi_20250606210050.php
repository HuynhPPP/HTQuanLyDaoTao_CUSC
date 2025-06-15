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
        'MaLichThi',
        'MaLop',
        'MaMH',
        'NgayThi',
        'KhungGio',
        'PhongThi',
        'HinhThucThi',
        'LanThi',
        'GhiChu'
    ];

    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class, 'MaMH', 'MaMH');
    }

    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'MaLop', 'MaLop');
    }

    public function phongThi()
    {
        return $this->belongsTo(phonghoc::class, 'PhongThi', 'TenPhong');
    }

    public function phanCongThi()
    {
        return $this->hasMany(PhieuPhanCongThi::class, 'MaLichThi', 'MaLichThi');
    }

    public function canBos()
    {
        return $this->hasMany(PhieuPhanCongThi::class, 'MaLichThi', 'MaLichThi');
    }

}
