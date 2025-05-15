<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ngaytuhoc extends Model
{
    use HasFactory;
    protected $table='NgayTuHoc';
    public $timestamps = false;
    protected $primaryKey='MaNgayTuHoc';
    protected $keyType='string';
    protected $fillable=[
        'MaNgayTuHoc' ,
	    'TenNgayTuHoc' ,
	    'NgayBDTuHoc' ,
	    'NgayKTTuHoc' ,
	    'TenTKB',
    ];
    public function tkb()
    {
        return $this->belongsTo(TKB::class, 'TenTKB');
    }
}
