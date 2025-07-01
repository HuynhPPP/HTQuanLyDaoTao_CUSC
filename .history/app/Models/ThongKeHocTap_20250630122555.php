<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThongKeHocTap extends Model
{
    use HasFactory;

    protected $table = 'thong_ke_hoc_tap';
    
    protected $fillable = [
        'ma_chuong_trinh',
        'hoc_ki',
        'tong_sinh_vien',
        'sinh_vien_gioi',
        'sinh_vien_kha',
        'sinh_vien_trung_binh',
        'sinh_vien_yeu',
        'diem_trung_binh_tong_khoa',
        'ty_le_tot_nghiep'
    ];

    public function chuongTrinh()
    {
        return $this->belongsTo(ChuongTrinhDaoTao::class, 'ma_chuong_trinh');
    }
}
