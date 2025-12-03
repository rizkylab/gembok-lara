<?php

namespace Database\Seeders;

use App\Models\MonthlySummary;
use Illuminate\Database\Seeder;

class MonthlySummarySeeder extends Seeder
{
    public function run(): void
    {
        $months = [
            ['year' => 2024, 'month' => 10],
            ['year' => 2024, 'month' => 11],
            ['year' => 2024, 'month' => 12],
        ];

        foreach ($months as $period) {
            MonthlySummary::create([
                'year' => $period['year'],
                'month' => $period['month'],
                'total_customers' => rand(50, 100),
                'active_customers' => rand(40, 90),
                'monthly_invoices' => rand(40, 90),
                'voucher_invoices' => rand(10, 30),
                'paid_monthly_invoices' => rand(30, 80),
                'paid_voucher_invoices' => rand(8, 25),
                'unpaid_monthly_invoices' => rand(5, 15),
                'unpaid_voucher_invoices' => rand(2, 8),
                'monthly_revenue' => rand(20000000, 50000000),
                'voucher_revenue' => rand(5000000, 15000000),
                'monthly_unpaid' => rand(2000000, 8000000),
                'voucher_unpaid' => rand(500000, 2000000),
                'total_revenue' => rand(25000000, 65000000),
                'total_unpaid' => rand(2500000, 10000000),
                'notes' => rand(0, 1) ? 'Bulan yang baik' : null,
            ]);
        }
    }
}
