<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherDeliveryLog extends Model
{
    protected $fillable = [
        'purchase_id',
        'phone',
        'status',
        'error_message',
    ];

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function purchase()
    {
        return $this->belongsTo(VoucherPurchase::class);
    }
}
