<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherOnlineSetting extends Model
{
    protected $fillable = [
        'package_id',
        'profile',
        'enabled',
        'duration',
        'duration_type',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];
}
