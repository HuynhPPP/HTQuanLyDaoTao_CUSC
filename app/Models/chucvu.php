<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chucvu extends Model
{
    use HasFactory;
    protected $table='ChucVu';
    protected $fillable = [
        'TenChucVu',
        'SoChucVu',
        'KinhNghiemLamviec',
        'ThoiGianBDCongTac',
        'ThoiGianKTCongTac',
        'DangCongTac',
    ];
}
