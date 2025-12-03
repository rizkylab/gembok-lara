<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CableMaintenanceLog extends Model
{
    protected $fillable = [
        'cable_route_id',
        'network_segment_id',
        'maintenance_type',
        'description',
        'performed_by',
        'maintenance_date',
        'duration_hours',
        'cost',
        'notes',
    ];

    protected $casts = [
        'maintenance_date' => 'date',
        'duration_hours' => 'decimal:2',
        'cost' => 'decimal:2',
    ];

    public function cableRoute()
    {
        return $this->belongsTo(CableRoute::class);
    }

    public function networkSegment()
    {
        return $this->belongsTo(NetworkSegment::class);
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class, 'performed_by');
    }
}
