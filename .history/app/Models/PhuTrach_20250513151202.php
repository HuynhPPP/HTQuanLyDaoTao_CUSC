<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class phutrach extends Model
{
    use HasFactory;
    protected $table='phutrach';
    protected $primaryKey='CongViecPhuTrach';
    protected $keyType='string';
    protected $fillable = [
        'CongViecPhuTrach',
        'MieuTaChiTiet',
    ];
}
