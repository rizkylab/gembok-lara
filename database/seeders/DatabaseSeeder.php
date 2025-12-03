<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Core Settings
            UserSeeder::class,
            AppSettingSeeder::class,
            
            // Package & Pricing
            PackageSeeder::class,
            VoucherPricingSeeder::class,
            
            // Staff
            TechnicianSeeder::class,
            CollectorSeeder::class,
            
            // Agent System
            AgentSeeder::class,
            AgentBalanceSeeder::class,
            AgentTransactionSeeder::class,
            AgentBalanceRequestSeeder::class,
            AgentNotificationSeeder::class,
            
            // Network Infrastructure
            OdpSeeder::class,
            NetworkSegmentSeeder::class,
            
            // Customers & Invoices
            CustomerSeeder::class,
            InvoiceSeeder::class,
            
            // Cable & ONU
            CableRouteSeeder::class,
            OnuDeviceSeeder::class,
            CableMaintenanceLogSeeder::class,
            
            // Voucher System
            VoucherPurchaseSeeder::class,
            VoucherGenerationSettingSeeder::class,
            VoucherOnlineSettingSeeder::class,
            VoucherDeliveryLogSeeder::class,
            
            // Agent Payments & Sales
            AgentPaymentSeeder::class,
            AgentMonthlyPaymentSeeder::class,
            AgentVoucherSaleSeeder::class,
            
            // Summary
            MonthlySummarySeeder::class,
        ]);
    }
}
