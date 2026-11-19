<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->boolean('customer_confirmation_required')
                ->default(false);

            $table->string('customer_confirmation_status')
                ->nullable();

            $table->text('customer_resolution_note')
                ->nullable();

            $table->timestamp('customer_confirmed_at')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'customer_confirmation_required',
                'customer_confirmation_status',
                'customer_resolution_note',
                'customer_confirmed_at',
            ]);
        });
    }
};
