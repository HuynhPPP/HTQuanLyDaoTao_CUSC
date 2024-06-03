<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lophoc extends Model
{
    use HasFactory;
    protected $table='LopHoc';
    protected  $fillable=[
        'MaLop'  ,
        'TenLop' ,
        'NgayBatDau' ,
        'SiSoBanDau' ,
        'SiSoHienTai' ,
        'MaChuongTrinh' ,
        'MaGhiChu' ,
    ];
    public function loaidaotao(){
        return $this->belongsTo('ChuongTrinh'::class,'MaChuongTrinh');
    }
    public function chitiettinhtrang(){
        return $this->belongsTo('ChiTietTinhTrang'::class,'MaGhiChu');
    }


}
