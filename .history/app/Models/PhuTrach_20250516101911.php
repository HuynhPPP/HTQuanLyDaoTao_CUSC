<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhuTrach extends Model
{
    use HasFactory;
    protected $table = 'phutrach';
    protected $primaryKey = 'CongViecPhuTrach';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'CongViecPhuTrach', 
        'MieuTaChiTiet'
    ];

    // Relationship với bảng cán bộ
    public function canBos()
    {
        return $this->hasMany(CanBo::class, 'CongViecPhuTrach', 'CongViecPhuTrach');
    }
}
