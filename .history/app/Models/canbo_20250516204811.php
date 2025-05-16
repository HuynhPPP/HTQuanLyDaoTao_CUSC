<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class canbo extends Model
{
    protected $table = 'canbo';
    protected $primaryKey = 'MaCB';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'MaCB', 'HoTenCB', 'GioiTinh', 'Email', 'Sdt', 
        'MaHV', 'TenChucVu', 'MaDV', 'MaBang', 'MaTapHuan', 
        'CongViecPhuTrach', 'ThoiGianBDCongTacCUSC', 'ThoiGianKTCongTacCUSC'
    ];

    // Quan hệ với Học Vị
    public function hocvi()
    {
        return $this->belongsTo(hocvi::class, 'MaHV', 'MaHV');
    }

    // Quan hệ với Chức Vụ
    public function chucvu()
    {
        return $this->belongsTo(chucvu::class, 'TenChucVu', 'TenChucVu');
    }

    // Quan hệ với Đơn Vị
    public function donvi()
    {
        return $this->belongsTo(donvi::class, 'MaDV', 'MaDV');
    }

    // Quan hệ với Bằng Cấp
    public function bangcapcanbo()
    {
        return $this->belongsTo(bangcapcanbo::class, 'MaBang', 'MaBang');
    }

    // Quan hệ với Tập Huấn
    public function taphuan()
    {
        return $this->belongsTo(TapHuan::class, 'MaTapHuan', 'MaTapHuan');
    }

    // Quan hệ với Phụ Trách
    public function phutrach()
    {
        return $this->belongsTo(phutrach::class, 'CongViecPhuTrach', 'CongViecPhuTrach');
    }
}