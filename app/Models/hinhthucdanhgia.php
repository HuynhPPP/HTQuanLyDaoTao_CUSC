<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hinhthucdanhgia extends Model
{
    use HasFactory;
    protected $table='HinhThucDanhGia';
    protected $primaryKey='MaHTDanhGia';
    protected $keyType='string';
    protected $fillable = [
        'MaHTDanhGia',
        'LT',
        'TH',
        'Assignment',
        'BCProject',
        'BaiTapLon',
    ];
}
