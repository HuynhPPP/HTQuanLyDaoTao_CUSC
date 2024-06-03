<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class theodoimhsapbatdau extends Model
{
    use HasFactory;
    protected  $table='TheoDoiMHSapBatDau';
    protected $fillable = [
        'MaTheoDoiMH' ,
        'TenMH'  ,
        'MaLop' ,
        'TenPhong'  ,
        'NgayBatDau' ,
        'GioHoc' ,
        'HocKy' ,
        'MaTTMH' ,
    ];
    public function lophoc(){
        return $this->belongsTo('LopHoc'::class,'MaLop');
    }
    public function phonghoc(){
        return $this->belongsTo('PhongHoc'::class,'TenPhong');
    }
    public function monhoc(){
        return $this->belongsTo('MonHoc'::class,'TenMH');
    }
    public function trangthaimh(){
        return $this->belongsTo('TrangThaiMH'::class,'MaTTMH');
    }
}
