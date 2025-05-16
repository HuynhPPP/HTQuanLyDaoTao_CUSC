<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class giaovien extends Model
{
    use HasFactory;

    protected $table = 'giaovien';
    protected $primaryKey = 'MaGV';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'MaGV', 'HoTenGV', 'GioiTinh', 'Email', 'Sdt', 
        'MaHV', 'TenChucVu', 'MaDV', 'MaBang', 
        'LoaiGV', 'ChuyenNganh', 'GhiChu', 
        'NgayBatDauCongTac', 'NgayKetThucCongTac'
    ];

    // Quan hệ với Học Vị
    public function hocvi()
    {
        return $this->belongsTo(hocvi::class, 'MaHV', 'MaHV');
    }

    // Quan hệ với Chức Vụ
    public function chucvu()
    {
        return $this->belongsTo(chucvu::class, 'TenChucVu', 'TenChucVu');
    }

    // Quan hệ với Đơn Vị
    public function donvi()
    {
        return $this->belongsTo(donvi::class, 'MaDV', 'MaDV');
    }

    // Quan hệ với Bằng Cấp
    public function bangcapcanbo()
    {
        return $this->belongsTo(bangcapcanbo::class, 'MaBang', 'MaBang');
    }
}
