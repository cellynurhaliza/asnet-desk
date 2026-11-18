<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                ->constrained('customer')
                ->restrictOnDelete();

            $table->foreignId('kategori_id')
                ->constrained('category')
                ->restrictOnDelete();

            $table->foreignId('technician_id')
                ->nullable()
                ->constrained('technician')
                ->nullOnDelete();

            $table->string('name');
            $table->text('complaint');
            $table->text('action')->nullable();

            $table->timestamp('reporting_time');

            $table->string('ticket_status')->default('New');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
