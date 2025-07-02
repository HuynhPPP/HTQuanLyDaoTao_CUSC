<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThongKeBaoCaoDoAn extends Model
{
    use HasFactory;

    protected $table = 'thong_ke_bao_cao_do_an'; // Tên bảng trong database

    protected $fillable = [
        'class_id',
        'instructor_id',
        'reviewer_id',
        'report_date',
        'report_time_start',
        'report_time_end',
        'location',
        'report_name',
    ];

    // Định nghĩa quan hệ
    public function class()
    {
        // 'class_id' là khóa ngoại trong bảng thong_ke_bao_cao_do_an
        // 'MaLop' là khóa chính trong bảng lophoc
        return $this->belongsTo(lophoc::class, 'class_id', 'MaLop');
    }

    public function instructor()
    {
        // 'instructor_id' là khóa ngoại trong bảng thong_ke_bao_cao_do_an
        // 'MaGV' là khóa chính trong bảng giaovien
        return $this->belongsTo(giaovien::class, 'instructor_id', 'MaGV');
    }

    public function reviewer()
    {
        // 'reviewer_id' là khóa ngoại trong bảng thong_ke_bao_cao_do_an
        // 'MaGV' là khóa chính trong bảng giaovien
        return $this->belongsTo(giaovien::class, 'reviewer_id', 'MaGV');
    }
}