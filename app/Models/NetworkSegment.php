<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NetworkSegment extends Model
{
    protected $fillable = [
        'name',
        'start_odp_id',
        'end_odp_id',
        'segment_type',
        'cable_length',
        'status',
        'installation_date',
        'notes',
    ];

    protected $casts = [
        'cable_length' => 'decimal:2',
        'installation_date' => 'date',
    ];

    public function startOdp()
    {
        return $this->belongsTo(Odp::class, 'start_odp_id');
    }

    public function endOdp()
    {
        return $this->belongsTo(Odp::class, 'end_odp_id');
    }

    public function maintenanceLogs()
    {
        return $this->hasMany(CableMaintenanceLog::class);
    }
}
