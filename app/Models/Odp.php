<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Odp extends Model
{
    protected $fillable = [
        'name',
        'code',
        'parent_odp_id',
        'latitude',
        'longitude',
        'address',
        'capacity',
        'used_ports',
        'status',
        'installation_date',
        'notes',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'capacity' => 'integer',
        'used_ports' => 'integer',
        'installation_date' => 'date',
    ];

    public function parentOdp()
    {
        return $this->belongsTo(Odp::class, 'parent_odp_id');
    }

    public function childOdps()
    {
        return $this->hasMany(Odp::class, 'parent_odp_id');
    }

    public function cableRoutes()
    {
        return $this->hasMany(CableRoute::class);
    }

    public function onuDevices()
    {
        return $this->hasMany(OnuDevice::class);
    }

    public function networkSegmentsStart()
    {
        return $this->hasMany(NetworkSegment::class, 'start_odp_id');
    }

    public function networkSegmentsEnd()
    {
        return $this->hasMany(NetworkSegment::class, 'end_odp_id');
    }

    public function getAvailablePortsAttribute()
    {
        return $this->capacity - $this->used_ports;
    }

    public function getUsagePercentageAttribute()
    {
        return $this->capacity > 0 ? ($this->used_ports / $this->capacity) * 100 : 0;
    }
}
