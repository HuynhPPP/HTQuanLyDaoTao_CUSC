<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chitiettinhtrang extends Model
{
    use HasFactory;
    protected $table='ChiTietTinhTrang';
    protected $primaryKey='MaGhiChu';
    protected $keyType='string';
    protected $fillable=[
        'MaGhiChu' ,
	    'LiDoNghiHoc' ,
	    'ThoiGianNghiHoc' ,
	    'MaSV' ,
    ];
    public function sinhvien(){
        return $this->belongsTo('SinhVien'::class,'MaSV');
    }
}
