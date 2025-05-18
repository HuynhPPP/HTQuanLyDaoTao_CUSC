<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThongTinTuyenSinh extends Model
{
    protected $table = 'thongtintuyensinh';
    protected $primaryKey = 'MaTS';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'MaTS',
        'NamTS',
        'DotTS', 
        'NgayBatDau',
        'NgayKetThuc',
        'ChiTieuTS'
    ];
}