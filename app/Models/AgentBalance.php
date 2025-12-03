<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentBalance extends Model
{
    protected $fillable = [
        'agent_id',
        'balance',
        'last_updated',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'last_updated' => 'datetime',
    ];

    public $timestamps = false;

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
