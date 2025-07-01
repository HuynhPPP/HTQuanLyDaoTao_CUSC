<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HinhThucDanhGia extends Model
{
    use HasFactory;

    protected $table = 'hinh_thuc_danh_gia';

    protected $fillable = [
        'MaChuongTrinh',
        'HinhThuc',
        'TiLePhanTram',
        'SoBaiThi',
        'DiemMoiBai',
        'ThoiGian',
        'DonViThoiGian',
    ];

    // Quan hệ với Chương trình
    public function chuongTrinh()
    {
        return $this->belongsTo(ChuongTrinh::class, 'MaChuongTrinh', 'MaChuongTrinh');
    }

    // Phương thức xoá hình thức đánh giá
    public static function xoaHinhThucDanhGia($id)
    {
        return self::where('id', $id)->delete();
    }

    // Phương thức xoá nhiều hình thức đánh giá
    public static function xoaNhieuHinhThucDanhGia($ids)
    {
        return self::whereIn('id', $ids)->delete();
    }
}

