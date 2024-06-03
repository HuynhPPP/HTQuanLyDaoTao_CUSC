<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chuongtrinh extends Model
{
    use HasFactory;
    protected $table= 'ChuongTrinh';
    protected $fillable = [
        'MaChuongTrinh',
        'TenChuongTrinh',
        'PhienBan',
        'NgayTrienKhaiPB',
    ];
}
