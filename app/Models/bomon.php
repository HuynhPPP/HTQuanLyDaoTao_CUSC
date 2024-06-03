<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bomon extends Model
{
    use HasFactory;
    protected $table='BoMon';
    protected $fillable=[
        'MaBM',
        'TenBMHienTai',
        'TenBMTungCongTac',
    ];
}
