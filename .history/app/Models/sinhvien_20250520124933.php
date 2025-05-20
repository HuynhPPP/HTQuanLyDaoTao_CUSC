<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sinhvien extends Model
{
    use HasFactory;
    protected $table='SinhVien';
    protected $primaryKey='MaSV';
    protected $keyType='string';
    protected  $fillable=[
        'MaSV',
        'MaEnroll',
        'HoTen',
        'InDebt',
        'NgaySinh',
        'GioiTinh',
        'SoCCCD',
        'NgayCap',
        'NoiCap',
        'Sdt',
        'NoiSinh',
        'DiaChi',
        'Zalo',
        'Receipt',
        'Invoice' ,
        'Billing' ,
        'Coll' ,
        'Billing(VND)' ,
        'Coll(VND)' ,
        'Discount' ,
        'LiDo' ,
        'NgayDangKi',
        'HoTenNguoiThan' ,
        'MoiQuanHe',
        'SdtNguoiThan' ,
        'ZaloNguoiThan' ,
        'EmailNguoiThan' ,
        'Email' ,
        'EmailCUSC' ,
        'Size' ,
    ];

    // Quan hệ với các bảng khác
    public function hosotuyensinh()
    {
        return $this->hasOne(HoSoTuyenSinh::class, 'MaSV', 'MaSV');
    }

    public function danhSachLop()
    {
        return $this->hasMany(danhsachsv::class, 'MaSV', 'MaSV');
    }
}
