<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->id();

            $table->string('username')->unique();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->string('password');

            $table->text('address')->nullable();

            $table->enum('service_type', [
                'Hotspot',
                'PPPoE',
                'VPN',
                'Other',
            ])->default('Hotspot');

            $table->enum('account_type', [
                'Personal',
                'Business',
            ])->default('Personal');

            $table->string('coordinates')->nullable();
            $table->string('photo')->nullable();

            $table->enum('status', [
                'Active',
                'Banned',
                'Disabled',
                'Inactive',
                'Limited',
                'Suspended',
            ])->default('Active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
