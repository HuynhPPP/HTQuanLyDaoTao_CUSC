<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hocki extends Model
{
    use HasFactory;
    protected $table='HocKi';
    protected $fillable = [
        'MaHK',
        'TenHK',
        'TongGioGoc',
        'TongGioTrienKhai' ,
 	    'MaChuongTrinh' ,
    ];
}
