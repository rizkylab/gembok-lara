<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('voucher_purchases', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->integer('amount');
            $table->text('description')->nullable();
            $table->string('type')->default('voucher');
            $table->string('voucher_package');
            $table->integer('voucher_quantity')->default(1);
            $table->string('voucher_profile');
            $table->text('voucher_data')->nullable(); // JSON
            $table->string('status')->default('pending');
            $table->string('payment_gateway')->nullable();
            $table->string('payment_transaction_id')->nullable();
            $table->string('payment_url')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('voucher_delivery_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('voucher_purchases')->cascadeOnDelete();
            $table->string('phone');
            $table->string('status');
            $table->text('error_message')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('voucher_generation_settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key')->unique();
            $table->text('setting_value');
            $table->timestamps();
        });

        Schema::create('voucher_online_settings', function (Blueprint $table) {
            $table->id();
            $table->string('package_id')->unique();
            $table->string('profile');
            $table->boolean('enabled')->default(true);
            $table->integer('duration')->default(24);
            $table->string('duration_type')->default('hours');
            $table->timestamps();
        });

       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_system_tables');
    }
};
