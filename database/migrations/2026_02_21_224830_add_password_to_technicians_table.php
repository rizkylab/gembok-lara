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
        Schema::table('technicians', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
            $table->string('password')->nullable()->after('email');
        });

        // Set default values for existing records
        $technicians = \App\Models\Technician::all();
        foreach ($technicians as $technician) {
            $username = $technician->email ? explode('@', $technician->email)[0] : strtolower(str_replace(' ', '', $technician->name)) . $technician->id;
            $technician->update([
                'username' => $username,
                'password' => \Illuminate\Support\Facades\Hash::make('password')
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('technicians', function (Blueprint $table) {
            $table->dropColumn(['username', 'password']);
        });
    }
};
