<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chuongtrinh extends Model
{
    use HasFactory;
    protected $table= 'ChuongTrinh';
    protected $primaryKey='MaChuongTrinh';
    protected $keyType='string';
    protected $fillable = [
        'MaChuongTrinh',
        'TenChuongTrinh',
        'PhienBan',
        'NgayTrienKhaiPB',
        'TenKhoaDaoTao',
    ];
}
