<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LdapAccount extends Model
{
    protected $table = 'ldap_accounts';

    protected $fillable = [
        'MaTaiKhoan',
        'username',
        'email',
        'full_name',
        'initial_password',
        'role',
        'is_sent',
        'is_active'
    ];

    public function getUser()
{
    // Thử lấy sinh viên
    $user = \App\Models\SinhVien::where('MaSV', $this->MaTaiKhoan)->first();

    if (!$user) {
        // Thử lấy giáo viên
        $user = \App\Models\GiaoVien::where('MaGV', $this->MaTaiKhoan)->first();
    }

    if (!$user) {
        // Thử lấy cán bộ
        $user = \App\Models\CanBo::where('MaCB', $this->MaTaiKhoan)->first();
    }

    return $user;
}

    // Scope để lọc các tài khoản chưa gửi
    public function scopeNotSent($query)
    {
        return $query->where('is_sent', false);
    }

    // Scope để lọc các tài khoản còn hoạt động
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
