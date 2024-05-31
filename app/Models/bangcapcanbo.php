<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bangcapcanbo extends Model
{
    use HasFactory;
    protected $table='BangCapCanBo';
    //protected $primaryKey ='MaCB';
    public $timestamps = false;
    protected $fillable = [
        'MaBangCap',
        'TenBangCap',
        'ChuyenMon',
        'ThoiGianCap',
        'DonViCap',
        'SoVaoSo',
        'SoHieu',
    ];
}
