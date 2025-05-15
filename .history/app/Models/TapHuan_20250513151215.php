<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TapHuan extends Model
{
    use HasFactory;

    // Bảng được liên kết với model
    protected $table = 'taphuan';

    // Khóa chính của bảng
    protected $primaryKey = 'MaTapHuan';
    protected $keyType='string';

    // Các thuộc tính có thể gán
    protected $fillable = [
        'MaTapHuan',
        'TenKhoaTapHuan',
        'ThoiGianBatDau',
        'ThoiGianKetThuc',
        'DiaDiem',
    ];

    // Bỏ qua các trường timestamps
    public $timestamps = false;
}
