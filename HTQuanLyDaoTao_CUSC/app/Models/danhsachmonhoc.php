<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class danhsachmonhoc extends Model
{
    use HasFactory;
    protected $table = 'DanhSachMH';

    public $timestamps = false; // Disable timestamp columns if not used
    protected $keyType='string';
    protected $primaryKey='MaHK';
    protected $fillable=[
        'TenKhungGio',
        'MaHK',
        'SttMH' ,
	    'TenMH' ,
    ];

    public function khungGio()
    {
        return $this->belongsTo(KhungGio::class, 'TenKhungGio');
    }

    public function hocki()
    {
        return $this->belongsTo(hocki::class, 'MaHK');
    }

    public function monhoc(){
        return $this->belongsTo(monhoc::class,'TenMH');
    }
}
