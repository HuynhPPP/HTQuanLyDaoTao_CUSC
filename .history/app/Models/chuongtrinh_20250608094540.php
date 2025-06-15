<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChuongTrinh extends Model
{
    use HasFactory;
    protected $table = 'chuongtrinh';
    protected $primaryKey = 'MaChuongTrinh';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'MaChuongTrinh', 
        'TenChuongTrinh', 
        'PhienBan', 
        'NgayTrienKhaiPB', 
        'TenKhoaDaoTao', 
    ];

    public function khoadaotao(){
        return $this->belongsTo(khoadaotao::class,'TenKhoaDaoTao');
    }

    // Quan hệ với Hình Thức Đánh Giá
    public function hinhThucDanhGia()
    {
        return $this->hasMany(HinhThucDanhGia::class, 'MaChuongTrinh', 'MaChuongTrinh');
    }

    // Quan hệ với Tiêu Chí Xếp Loại
    public function tieuChiXepLoai()
    {
        return $this->hasMany(TieuChiXepLoai::class, 'MaChuongTrinh', 'MaChuongTrinh');
    }
}
