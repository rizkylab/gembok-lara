<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlySummary extends Model
{
    protected $table = 'monthly_summary';

    protected $fillable = [
        'year',
        'month',
        'total_customers',
        'active_customers',
        'monthly_invoices',
        'voucher_invoices',
        'paid_monthly_invoices',
        'paid_voucher_invoices',
        'unpaid_monthly_invoices',
        'unpaid_voucher_invoices',
        'monthly_revenue',
        'voucher_revenue',
        'monthly_unpaid',
        'voucher_unpaid',
        'total_revenue',
        'total_unpaid',
        'notes',
    ];

    protected $casts = [
        'monthly_revenue' => 'decimal:2',
        'voucher_revenue' => 'decimal:2',
        'monthly_unpaid' => 'decimal:2',
        'voucher_unpaid' => 'decimal:2',
        'total_revenue' => 'decimal:2',
        'total_unpaid' => 'decimal:2',
    ];
}
