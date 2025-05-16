<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bangcapcanbo extends Model
{
    use HasFactory;
    protected $table='bangcapcanbo';
    protected $primaryKey ='MaCB';
    protected $keyType='string';
    public $timestamps = false;
    protected $fillable = [
        'MaBang',
        'TenBang',
        'ThoiGianCap',
        'DonViCap',
        'SoHieu',
        'SoVaoSo',
    ];

    public function canBos()
    {
        return $this->hasMany(CanBo::class, 'TenChucVu', 'TenChucVu');
    }
}
