<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'username',
        'pppoe_username',
        'pppoe_password',
        'static_ip',
        'mac_address',
        'name',
        'phone',
        'email',
        'address',
        'latitude',
        'longitude',
        'package_id',
        'status',
        'join_date',
    ];

    protected $appends = ['odp_id'];

    public function getOdpIdAttribute()
    {
        return $this->cableRoutes()->latest()->first()->odp_id ?? null;
    }

    protected $casts = [
        'join_date' => 'datetime',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function cableRoutes()
    {
        return $this->hasMany(CableRoute::class);
    }

    public function onuDevices()
    {
        return $this->hasMany(OnuDevice::class);
    }
}
