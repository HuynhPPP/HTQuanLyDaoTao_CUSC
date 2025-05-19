<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class khoadaotao extends Model
{
    use HasFactory;
    protected $table='KhoaDaoTao';
    protected $primaryKey='TenKhoaDaoTao';
    protected $keyType='string';
    protected $fillable=[
        'TenKhoaDaoTao',
        'ThoiGianDaoTao',
    ];
    public $timestamps = true;
}
