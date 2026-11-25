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
        Schema::table('ticket_photos', function (Blueprint $table) {
            $table->foreignId('activity_id')
                ->nullable()
                ->after('ticket_id')
                ->constrained('ticket_activities')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ticket_photos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('activity_id');
        });
    }
};
