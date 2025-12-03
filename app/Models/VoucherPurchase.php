<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherPurchase extends Model
{
    protected $fillable = [
        'customer_name',
        'customer_phone',
        'amount',
        'description',
        'type',
        'voucher_package',
        'voucher_quantity',
        'voucher_profile',
        'voucher_data',
        'status',
        'payment_gateway',
        'payment_transaction_id',
        'payment_url',
        'completed_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'voucher_quantity' => 'integer',
        'completed_at' => 'datetime',
    ];

    public function deliveryLogs()
    {
        return $this->hasMany(VoucherDeliveryLog::class, 'purchase_id');
    }

    public function getVoucherDataArrayAttribute()
    {
        return $this->voucher_data ? json_decode($this->voucher_data, true) : [];
    }
}
