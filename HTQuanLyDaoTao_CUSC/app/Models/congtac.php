<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class congtac extends Model
{
    use HasFactory;
    protected $table='CongTac';
    protected $primaryKey='MaCT';
    protected $keyType='string';
    protected $fillable = [
        'MaCT',
        'NamBatDauCongTac',
        'NamBatDauCTCUSC',
        'NamKTCongTacCUSC',
    ];
}
