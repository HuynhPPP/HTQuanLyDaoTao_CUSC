<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhieuPhanCongThi extends Model
{
    protected $table = 'phieuphancongthi';
    protected $primaryKey = 'MaPhanCong';
    public $timestamps = true;

    protected $fillable = [
        'MaLichThi',
        'MaGV',
        'VaiTro'
    ];

    public function lichThi()
    {
        return $this->belongsTo(LichThi::class, 'MaLichThi', 'MaLichThi');
    }

    public function giaoVien()
    {
        return $this->belongsTo(GiaoVien::class, 'MaGV', 'MaGV');
    }

    public function getNguoiPhanCongAttribute()
    {
        return $this->canBo ?? $this->giaoVien;
    }
}
