<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class danhsachmonhoc extends Model
{
    use HasFactory;
    protected $table = 'DanhSachMH';

    public $timestamps = false; // Disable timestamp columns if not used
    protected $fillable=[
        'TenKhungGio',
        'TenMH',
    ];

    public function khungGio()
    {
        return $this->belongsTo(KhungGio::class, 'TenKhungGio');
    }

    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class, 'TenMH');
    }
}
