<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class loaidaotao extends Model
{
    use HasFactory;
    protected $table='LoaiDaoTao';
    protected $fillable=[
        'TenKhoaDaoTao',
        'ThoiGianDaoTao',
        'ThoiGianBDDaoTao',
        'ThoiGianKTDaoTao',
        'MaChuongTrinh',
    ];
    public function loaidaotao(){
        return $this->belongsTo('ChuongTrinh'::class,'MaChuongTrinh');
    }

}
