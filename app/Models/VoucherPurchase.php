<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherPurchase extends Model
{
    protected $fillable = [
        'order_number',
        'pricing_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'amount',
        'voucher_code',
        'voucher_username',
        'voucher_password',
        'duration_hours',
        'status',
        'payment_method',
        'payment_transaction_id',
        'payment_url',
        'payment_response',
        'synced_to_mikrotik',
        'synced_to_radius',
        'wa_sent',
        'paid_at',
        'activated_at',
        'expires_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'duration_hours' => 'integer',
        'synced_to_mikrotik' => 'boolean',
        'synced_to_radius' => 'boolean',
        'wa_sent' => 'boolean',
        'paid_at' => 'datetime',
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function pricing()
    {
        return $this->belongsTo(VoucherPricing::class, 'pricing_id');
    }

    // Generate unique order number
    public static function generateOrderNumber(): string
    {
        $prefix = 'VCH';
        $date = now()->format('ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        return "{$prefix}{$date}{$random}";
    }

    // Generate voucher credentials
    public function generateVoucherCredentials(): void
    {
        $this->voucher_code = strtoupper(substr(md5(uniqid()), 0, 8));
        $this->voucher_username = 'vc' . strtolower(substr(md5($this->order_number), 0, 8));
        $this->voucher_password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 8);
        $this->save();
    }

    // Check if voucher is active
    public function isActive(): bool
    {
        return $this->status === 'completed' && 
               $this->activated_at && 
               ($this->expires_at === null || $this->expires_at->isFuture());
    }

    // Activate voucher
    public function activate(): void
    {
        $this->activated_at = now();
        $this->expires_at = now()->addHours($this->duration_hours);
        $this->status = 'completed';
        $this->save();
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
