<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GiangDay extends Pivot
{
    use HasFactory;

    protected $table = 'giangday';
    
    // Chỉ định rõ khóa chính và khóa ngoại
    protected $primaryKey = ['TenMH', 'MaGV'];
    public $incrementing = false;

    // Các trường có thể điền
    protected $fillable = [
        'TenMH', 
        'MaLop',
        'MaGV', 
        'NgayBatDau', 
        'NgayKetThuc', 
        'GhiChu'
    ];

    // Quan hệ với môn học
    public function monHoc()
    {
        return $this->belongsTo(monhoc::class, 'TenMH', 'TenMH');
    }

    // Quan hệ với giáo viên
    public function giaoVien()
    {
        return $this->belongsTo(GiaoVien::class, 'MaGV', 'MaGV');
    }
}
