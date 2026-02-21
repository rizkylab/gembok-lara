<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    protected $fillable = [
        'name',
        'username',
        'phone',
        'role',
        'email',
        'password',
        'notes',
        'is_active',
        'area_coverage',
        'join_date',
        'last_login',
        'whatsapp_group_id',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'join_date' => 'datetime',
        'last_login' => 'datetime',
    ];
}
