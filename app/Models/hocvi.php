<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hocvi extends Model
{
    use HasFactory;
    protected $table='HocVi';
    protected $fillable = [
        'TenHocVi',
        'SoLuongHocVi',
        'ThoiDiemNhanChungNhan',
        'TenCoQuanCap',
        'DiaDiem',
    ];
}
