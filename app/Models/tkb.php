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
    ];
    public function theodoimh(){
        return $this->belongsTo('TheoDoiMHSapBatDau'::class,'MaTheoDoiMH');
    }
   public function phonghoc(){
        return $this->belongsTo('PhongHoc'::class,'TenPhong');
    }
    public function lophoc(){
        return $this->belongsTo('LopHoc'::class,'MaLop');
    }

}
