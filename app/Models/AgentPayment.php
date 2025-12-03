<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentPayment extends Model
{
    protected $fillable = [
        'agent_id',
        'customer_id',
        'invoice_id',
        'amount',
        'payment_method',
        'notes',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public $timestamps = false;

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
