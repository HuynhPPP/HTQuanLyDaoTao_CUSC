<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class danhsachphong extends Model
{
    use HasFactory;
    protected $table = 'DanhSachPhong';

    public $timestamps = false; // Disable timestamp columns if not used
    protected $fillable = [
        'MaLop',
        'TenPhong',
    ];

    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'MaLop');
    }

    public function phongHoc()
    {
        return $this->belongsTo(PhongHoc::class, 'TenPhong');
    }
}
