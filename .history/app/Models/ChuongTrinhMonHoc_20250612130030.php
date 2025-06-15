<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChuongTrinhMonHoc extends Model
{
    protected $table = 'chuongtrinh_monhoc';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = [
        'MaChuongTrinh',
        'MaMH',
        'Stt'
    ];

    public function chuongTrinh()
    {
        return $this->belongsTo(ChuongTrinh::class, 'MaChuongTrinh', 'MaChuongTrinh');
    }

    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class, 'MaMH', 'MaMH');
    }

    public function hinhThucDanhGia()
    {
        return $this->hasMany(HinhThucDanhGia::class, 'MaChuongTrinh', 'MaChuongTrinh');
    }

    public function tieuChiXepLoai()
    {
        return $this->hasMany(TieuChiXepLoai::class, 'MaChuongTrinh', 'MaChuongTrinh');
    }

}
