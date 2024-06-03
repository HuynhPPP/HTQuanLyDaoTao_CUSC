<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class phonghoc extends Model
{
    use HasFactory;
    protected $table='PhongHoc';
    protected $fillable = [
        'TenPhong' ,
        'LoaiPhong' ,
        'SucChua',
    ];
}
