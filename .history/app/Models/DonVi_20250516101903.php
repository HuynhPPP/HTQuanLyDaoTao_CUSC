<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonVi extends Model
{
    use HasFactory;
    protected $table = 'donvi';
    protected $primaryKey = 'MaDV';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'MaDV', 
        'TenDVHienTai', 
        'TenDVTungCongTac'
    ];

    // Relationship với bảng cán bộ
    public function canBos()
    {
        return $this->hasMany(CanBo::class, 'MaDV', 'MaDV');
    }
}
