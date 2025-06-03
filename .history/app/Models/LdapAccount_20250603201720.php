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
