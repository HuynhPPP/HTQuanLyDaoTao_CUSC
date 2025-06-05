<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class monhoc extends Model
{
    use HasFactory;
    protected $table = 'MonHoc';
    protected $primaryKey = 'MaMH';
    protected $keyType = 'string';
    protected $fillable = [
        'TenMH',
        'MaMH',
        'GioGoc',
        'GioTrienKhai',
        'TietLT',
        'TietTH',
        'TietLTvaTH',
        'MaHTDanhGia',
    ];
    public function loaidaotao()
    {
        return $this->belongsTo('ChuongTrinh'::class, 'MaChuongTrinh');
    }
    public function hinhthucdanhgia()
    {
        return $this->belongsTo(HinhThucDanhGia::class, 'MaHTDanhGia', 'MaHTDanhGia');
    }
    public function giangViens()
    {
        return $this->belongsToMany(GiaoVien::class, 'giangday', 'MaMH', 'MaGV')
            ->withPivot('NgayBatDau', 'NgayKetThuc', 'GhiChu', 'MaLop')
            ->withTimestamps()
            ->using(GiangDay::class);
    }

}
