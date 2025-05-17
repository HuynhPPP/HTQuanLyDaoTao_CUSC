<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChuongTrinh extends Model
{
    use HasFactory;
    protected $table = 'chuongtrinh';
    protected $primaryKey = 'MaChuongTrinh';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'MaChuongTrinh', 
        'TenChuongTrinh', 
        'PhienBan', 
        'NgayTrienKhaiPB', 
        'TenKhoaDaoTao', 
    ];

    protected $dates = [
        'NgayTrienKhaiPB',
        'create_at',
        'update_at'
    ];
    public function khoadaotao(){
        return $this->belongsTo(khoadaotao::class,'TenKhoaDaoTao');
    }
}
