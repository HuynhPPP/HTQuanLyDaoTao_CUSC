<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chucvu extends Model
{
    use HasFactory;
    protected $table='ChucVu';
    protected $primaryKey='TenChucVu';
    protected $keyType='string';
    protected $fillable = [
        'TenChucVu',
        'ThoiGianDamNhanCV',
        'ThoiGianKTCV',
    ];
}
