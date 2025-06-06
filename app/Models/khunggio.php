<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class khunggio extends Model
{
    use HasFactory;
    protected $table='KhungGio';
    protected $primaryKey='TenKhungGio';
    protected $keyType='string';
    public $timestamps = false;
    protected $fillable = [
        'TenKhungGio',
	    'ThoiGian',
    ];
}
