<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentVoucherSale extends Model
{
    protected $fillable = [
        'agent_id',
        'voucher_code',
        'package_id',
        'package_name',
        'customer_phone',
        'customer_name',
        'price',
        'commission',
        'status',
        'sold_at',
        'used_at',
        'notes',
        'agent_price',
        'commission_amount',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'commission' => 'decimal:2',
        'agent_price' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'sold_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public $timestamps = false;

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
