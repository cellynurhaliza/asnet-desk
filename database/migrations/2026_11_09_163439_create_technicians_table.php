
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technician', function (Blueprint $table) {
            $table->id();
            $table->string('technician_name');
            $table->string('phone_number')->nullable();
            $table->boolean('active_technician')->default(true);
            $table->string('telegram_username')->nullable();
            $table->string('role_telegram')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technician');
    }
};
