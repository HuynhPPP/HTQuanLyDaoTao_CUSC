<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class canbo extends Model
{
    use HasFactory;
    protected $table='CanBo';
    protected $fillable = [
        'MaCB',
        'HoTenCB',
        'GioiTinh',
        'Email',
        'Sdt',
        'TenHocVi',
        'TenChucVu',
        'LinhVucPhuTrach',
        'MaCT',
        'MaBM',
        'MaBang',
        'MaTapHuan',
    ];
    public function hocvi(){
        return $this->belongsTo('Hocvi'::class,'TenHocVi');
    }
    public function phutrach(){
        return $this->belongsTo('PhuTrach'::class,'LinhVucPhuTrach');
    }
    public function congtac(){
        return $this->belongsTo('Congtac'::class,'MaCT');
    }
    public function bomon(){
        return $this->belongsTo('BoMon'::class,'MaBM');
    }
    public function bangcapcanbo(){
        return $this->belongsTo('BangCapCanBo'::class,'MaBang');
    }
    public function taphuan(){
        return $this->belongsTo('TapHuan'::class,'MaTapHuan');
    }

}
