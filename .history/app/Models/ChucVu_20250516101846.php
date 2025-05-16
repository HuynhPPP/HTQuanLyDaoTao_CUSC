<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChucVu extends Model
{
    use HasFactory;
    protected $table = 'chucvu';
    protected $primaryKey = 'TenChucVu';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'TenChucVu', 
        'ThoiGianBatDauCV', 
        'ThoiGianKTCV'
    ];

    // Relationship với bảng cán bộ
    public function canBos()
    {
        return $this->hasMany(CanBo::class, 'TenChucVu', 'TenChucVu');
    }
}
