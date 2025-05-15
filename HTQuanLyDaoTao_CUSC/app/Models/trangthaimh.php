<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class trangthaimh extends Model
{
    use HasFactory;
    protected $table='TrangThaiMH';
    protected $fillable=[
        'MaTTMH' ,
	    'TrangThai',
    ];
}
