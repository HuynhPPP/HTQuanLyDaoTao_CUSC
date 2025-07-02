<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaoCaoDoAnUpload extends Model
{
    use HasFactory;

    protected $table = 'bao_cao_do_an_upload'; // Đảm bảo tên bảng đúng

    protected $fillable = [
        'ten_file',
        'duong_dan',
        'lop_bao_cao',
        'lan_bao_cao',
        'hoc_ky',
        'giang_vien_huong_dan',
        'giang_vien_phan_bien',
        'ngay_bao_cao',
        'gio_bao_cao_bat_dau',
        'gio_bao_cao_ket_thuc',
        'dia_diem_bao_cao',
        'so_luong_nhom',
        'nguoi_lap',
        'truong_bp_dao_tao',
        'ngay_lap',
    ];

    protected $casts = [
        'ngay_bao_cao' => 'date',
        'ngay_lap' => 'date',
    ];
}