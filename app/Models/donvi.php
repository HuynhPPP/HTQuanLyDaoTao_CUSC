<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class donvi extends Model
{
    use HasFactory;
    protected $table='DonVi';
    protected $fillable=[
        'MaDV',
        'TenDVHienTai',
        'TenDVTungCongTac',
    ];
}
