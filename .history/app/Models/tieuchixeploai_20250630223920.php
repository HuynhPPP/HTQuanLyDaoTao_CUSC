<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TieuChiXepLoai extends Model
{
    use HasFactory;

    protected $table = 'tieu_chi_xep_loai';

    protected $fillable = [
        'MaChuongTrinh',
        'XepLoai',
        'DiemTu',
        'DiemDen',
    ];

    // Quan hệ với Chương trình
    public function chuongTrinh()
    {
        return $this->belongsTo(ChuongTrinh::class, 'MaChuongTrinh', 'MaChuongTrinh');
    }
}
