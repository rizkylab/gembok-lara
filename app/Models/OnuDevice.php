<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnuDevice extends Model
{
    protected $fillable = [
        'name',
        'serial_number',
        'mac_address',
        'ip_address',
        'status',
        'latitude',
        'longitude',
        'customer_id',
        'odp_id',
        'ssid',
        'password',
        'model',
        'firmware_version',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function odp()
    {
        return $this->belongsTo(Odp::class);
    }
}
