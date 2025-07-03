<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThongKeBaoCaoDoAn extends Model
{
    use HasFactory;

    // Trong ThongKeBaoCaoDoAn.php
    public function class()
    {
        return $this->belongsTo(lophoc::class, 'class_id', 'MaLop');
    }
    public function instructor()
    {
        return $this->belongsTo(giaovien::class, 'instructor_id', 'MaGV');
    }
    public function reviewer()
    {
        return $this->belongsTo(giaovien::class, 'reviewer_id', 'MaGV');
    }
}
