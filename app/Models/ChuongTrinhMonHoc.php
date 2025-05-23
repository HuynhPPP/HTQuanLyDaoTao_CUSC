<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChuongTrinhMonHoc extends Model
{
    protected $table = 'chuongtrinh_monhoc';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = [
        'MaChuongTrinh',
        'TenMH',
        'Stt'
    ];

    public function chuongTrinh()
    {
        return $this->belongsTo(ChuongTrinh::class, 'MaChuongTrinh', 'MaChuongTrinh');
    }

    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class, 'TenMH', 'TenMH');
    }
}
