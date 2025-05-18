<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class monhoc extends Model
{
    use HasFactory;
    protected $table = 'MonHoc';
    protected $primaryKey = 'TenMH';
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

}
