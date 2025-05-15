<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hocvi extends Model
{
    use HasFactory;
    protected $table='HocVi';
    protected $primaryKey='MaHV';
    protected $keyType='string';
    protected $fillable = [
        'MaHV',
        'TenHocVi',
        'NganhHoc',
        'ChuyenNganh' ,
 	    'CoSoDaoTao' ,
 	    'NamCap' ,
 	    'HinhThucDaoTao'
    ];

    public function canbos()
    {
        return $this->hasMany(canbo::class, 'MaHV');
    }
}
