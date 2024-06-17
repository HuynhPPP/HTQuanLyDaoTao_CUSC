<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class danhsachngaynghi extends Model
{
    use HasFactory;
    protected $table = 'DanhSachNgayNghi';

    public $timestamps = false; // Disable timestamp columns if not used
    protected $fillable = [
        'TenTKB',
        'MaNgayNghi',
    ];

    public function ngayNghi()
    {
        return $this->belongsTo(NgayNghi::class, 'MaNgayNghi');
    }

    public function tkb()
    {
        return $this->belongsTo(TKB::class, 'TenTKB');
    }

}
