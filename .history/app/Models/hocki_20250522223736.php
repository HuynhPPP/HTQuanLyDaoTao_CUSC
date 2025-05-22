<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hocki extends Model
{
    use HasFactory;
    protected $table='HocKi';
    protected $primaryKey='MaHK';
    protected $keyType='string';
    protected $fillable = [
        'MaHK',
        'TenHK', 
        'TongGioGoc',
        'TongGioTrienKhai',
        'MaChuongTrinh'
    ];

    public function chuongTrinh()
    {
        return $this->belongsTo(ChuongTrinh::class, 'MaChuongTrinh', 'MaChuongTrinh');
    }
}
