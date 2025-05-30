<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ADAccount extends Model
{
    protected $table = 'ad_accounts';
    
    protected $fillable = [
        'username',
        'display_name', 
        'email',
        'user_type'
    ];
}