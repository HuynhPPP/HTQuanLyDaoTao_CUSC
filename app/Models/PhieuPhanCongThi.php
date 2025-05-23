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
        'MaCB',
        'VaiTro'
    ];

    public function lichThi()
    {
        return $this->belongsTo(LichThi::class, 'MaLichThi', 'MaLichThi');
    }

    public function canBo()
    {
        return $this->belongsTo(CanBo::class, 'MaCB', 'MaCB');
    }
}
