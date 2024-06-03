<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class monhoc extends Model
{
    use HasFactory;
    protected $table='MonHoc';
    protected  $fillable=[
        'TenMH',
        'MaMH',
        'MaChuongTrinh',
        'GioGoc',
        'GioTrienKhai',
        'SoTietLT',
        'SoTietTH',
        'SoTietLTvaTH',
        'MaHTDanhGia',
    ];
    public function loaidaotao(){
        return $this->belongsTo('ChuongTrinh'::class,'MaChuongTrinh');
    }
    public function hinhthucdg(){
        return $this-> belongsTo('HinhThucDanhGia'::class,'MaHTDanhGia');
    }

}
