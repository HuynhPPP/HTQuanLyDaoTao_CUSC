<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tkb extends Model
{
    use HasFactory;
    protected $table='TKB';
    public $timestamps = false;
    protected $primaryKey='TenTKB';
    protected $keyType='string';
    protected $fillable=[
        'TenTKB' ,
        'MaLop' ,
        'MaHK',
        'NgayHoc' ,
        'NgayPhienBan'
    ];
    public function hocKi()
    {
        return $this->belongsTo(HocKi::class, 'MaHK');
    }

    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'MaLop');
    }
}
