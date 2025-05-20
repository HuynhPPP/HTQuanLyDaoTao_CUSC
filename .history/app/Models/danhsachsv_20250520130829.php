<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class danhsachsv extends Model
{
    use HasFactory;
    protected $table = 'danhsachsv';
    public $timestamps = false;
    protected $fillable = [
        'MaLop',
        'MaSV'
    ];

    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'MaSV', 'MaSV');
    }

    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'MaLop', 'MaLop');
    }
}
