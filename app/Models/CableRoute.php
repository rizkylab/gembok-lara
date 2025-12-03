<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CableRoute extends Model
{
    protected $fillable = [
        'customer_id',
        'odp_id',
        'cable_length',
        'cable_type',
        'installation_date',
        'status',
        'port_number',
        'notes',
    ];

    protected $casts = [
        'cable_length' => 'decimal:2',
        'installation_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function odp()
    {
        return $this->belongsTo(Odp::class);
    }

    public function maintenanceLogs()
    {
        return $this->hasMany(CableMaintenanceLog::class);
    }
}
