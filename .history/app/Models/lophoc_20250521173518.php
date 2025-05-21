<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lophoc extends Model
{
    use HasFactory;
    protected $table = 'LopHoc';
    protected $primaryKey = 'MaLop';
    protected $keyType = 'string';
    protected $fillable = [
        'MaLop',
        'TenLop',
        'NgayBatDau',
        'MaChuongTrinh',
    ];
    public function loaidaotao()
    {
        return $this->belongsTo(ChuongTrinh::class, 'MaChuongTrinh');
    }
    public function danhSachSinhVien()
    {
        return $this->hasMany(danhsachsv::class, 'MaLop', 'MaLop');
    }

}
