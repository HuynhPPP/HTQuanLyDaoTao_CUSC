<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GiangDay extends Pivot
{
    use HasFactory;

    protected $table = 'giangday';
    
    public $incrementing = false;

    // Các trường có thể điền
    protected $fillable = [
        'MaMH', 
        'MaLop',
        'MaGV', 
        'NgayBatDau', 
        'NgayKetThuc', 
        'GhiChu'
    ];

    // Quan hệ với môn học
    public function monHoc()
    {
        return $this->belongsTo(monhoc::class, 'MaMH', 'MaMH');
    }

    public function lopHoc()
    {
        return $this->belongsTo(monhoc::class, 'MaLop', 'MaLop');
    }

    // Quan hệ với giáo viên
    public function giaoVien()
    {
        return $this->belongsTo(GiaoVien::class, 'MaGV', 'MaGV');
    }
}
