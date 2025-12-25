<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voucher_purchases_order', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('pricing_id')->nullable();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('voucher_code')->nullable();
            $table->string('voucher_username')->nullable();
            $table->string('voucher_password')->nullable();
            $table->integer('duration_hours')->default(1);
            $table->string('status')->default('pending'); // pending, paid, completed, failed, expired
            $table->string('payment_method')->nullable(); // midtrans, xendit, manual
            $table->string('payment_transaction_id')->nullable();
            $table->string('payment_url')->nullable();
            $table->text('payment_response')->nullable();
            $table->boolean('synced_to_mikrotik')->default(false);
            $table->boolean('synced_to_radius')->default(false);
            $table->boolean('wa_sent')->default(false);
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->index('order_number');
            $table->index('status');
            $table->index('customer_phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher_purchases_order');
    }
};
