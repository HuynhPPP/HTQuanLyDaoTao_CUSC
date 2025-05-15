<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class canbo extends Model
{
    use HasFactory;
    protected $table='CanBo';
    protected $primaryKey='MaCB';
    protected $keyType='string';
    protected $fillable = [
        'MaCB',
        'HoTenCB',
        'GioiTinh',
        'Email',
        'Sdt',
        'MaHV',
        'TenChucVu',
        'CongViecPhuTrach',
        'MaDV',
        'MaBang',
        'MaTapHuan',
        'ThoiGianBDCongTacCUSC',
        'ThoiGianKTCongTacCUSC',
    ];
    public function hocvi(){
        return $this->belongsTo(hocvi::class,'MaHV');
    }
    public function phutrach(){
        return $this->belongsTo(phutrach::class,'CongViecPhuTrach');
    }
    public function bomon(){
        return $this->belongsTo(donvi::class,'MaDV');
    }
    public function bangcapcanbo(){
        return $this->belongsTo(bangcapcanbo::class,'MaBang');
    }
    public function taphuan(){
        return $this->belongsTo(TapHuan::class,'MaTapHuan');
    }

}
