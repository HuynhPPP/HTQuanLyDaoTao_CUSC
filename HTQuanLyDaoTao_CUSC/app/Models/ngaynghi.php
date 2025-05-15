<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ngaynghi extends Model
{
    use HasFactory;
    protected $table ='NgayNghi';
    protected $primaryKey='MaNgayNghi';
    protected $keyType='string';
    public $timestamps = false;
    protected $fillable = [
        'MaNgayNghi',
        'TenNgayNghi',
        'NgayBDNghi',
        'NgayKT',
    ];
}
