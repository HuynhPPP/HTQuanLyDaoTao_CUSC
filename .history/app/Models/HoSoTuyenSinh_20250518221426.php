<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoSoTuyenSinh extends Model
{
    protected $table = 'hosotuyensinh';
    protected $primaryKey = 'MaHoSo';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'MaHoSo',
        'MaSV',
        'NgayNopHS',
        'TrangThaiHS'
    ];

    public function thongTinTuyenSinh()
    {
        return $this->belongsTo(ThongTinTuyenSinh::class, 'MaTS', 'MaTS');
    }

    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'MaSV', 'MaSV');
    }
}