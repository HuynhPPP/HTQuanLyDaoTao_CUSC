<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tkb extends Model
{
    use HasFactory;
    protected $table='TKB';
    public $timestamps = false;
    protected $fillable=[
        'TenTKB' ,
        'MaLop' ,
        'TuanHoc' ,
        'TenPhong' ,
        'BuoiLyThuyet' ,
        'BuoiThucHanh' ,
        'MaTheoDoiMH' ,
        'PhongLT',
        'PhongTH',
    ];
    public function theodoimh(){
        return $this->belongsTo('TheoDoiMHSapBatDau'::class,'MaTheoDoiMH');
    }
    public function phonglt(){
        return $this->belongsTo('PhongHoc'::class,'PhongLT');
    }public function phongth(){
        return $this->belongsTo('PhongHoc'::class,'PhongTH');
    }
    public function lophoc(){
        return $this->belongsTo('LopHoc'::class,'MaLop');
    }

}
