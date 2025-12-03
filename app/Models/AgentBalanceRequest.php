<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentBalanceRequest extends Model
{
    protected $fillable = [
        'agent_id',
        'amount',
        'status',
        'admin_notes',
        'requested_at',
        'processed_at',
        'processed_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    public $timestamps = false;

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
