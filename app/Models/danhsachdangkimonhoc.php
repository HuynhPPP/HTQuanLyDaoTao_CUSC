<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class danhsachdangkimonhoc extends Model
{
    use HasFactory;
    protected $table = 'DanhSachDKMH';

    public $timestamps = false; // Disable timestamp columns if not used
    protected $fillable=[
        'TenKhungGio',
        'MaHK',
    ];

    public function khungGio()
    {
        return $this->belongsTo(KhungGio::class, 'TenKhungGio');
    }

    public function hocki()
    {
        return $this->belongsTo(hocki::class, 'MaHK');
    }

}
