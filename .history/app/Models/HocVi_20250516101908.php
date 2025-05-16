<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HocVi extends Model
{
    use HasFactory;
    protected $table = 'hocvi';
    protected $primaryKey = 'MaHV';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'MaHV', 
        'TenHocVi', 
        'NganhHoc', 
        'ChuyenNganh', 
        'CoSoDaoTao', 
        'NamCap', 
        'HinhThucDaoTao'
    ];

    // Relationship với bảng cán bộ
    public function canBos()
    {
        return $this->hasMany(CanBo::class, 'MaHV', 'MaHV');
    }
}
