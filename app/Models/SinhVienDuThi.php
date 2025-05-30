<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SinhVienDuThi extends Model
{
    protected $table = 'sinhvien_duthi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'MaSV',
        'MaLichThi',
        'MaLop',
        'TrangThaiDuThi',
        'GhiChu'
    ];

    // Quan hệ với sinh viên
    public function sinhVien()
    {
        return $this->belongsTo(sinhvien::class, 'MaSV', 'MaSV');
    }

    // Quan hệ với lịch thi
    public function lichThi()
    {
        return $this->belongsTo(LichThi::class, 'MaLichThi', 'MaLichThi');
    }

    // Quan hệ với lớp học
    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'MaLop', 'MaLop');
    }

    public function diem()
    {
        return $this->hasOne(DiemThi::class, 'MaSV', 'MaSV');
    }
}
