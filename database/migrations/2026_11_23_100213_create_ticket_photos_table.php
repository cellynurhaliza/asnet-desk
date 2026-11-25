<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ticket_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')
                ->constrained('tickets')
                ->cascadeOnDelete();

            $table->string('photo_path');
            $table->timestamps();
        });

        Schema::table('ticket_activities', function (Blueprint $table) {
            $table->foreignId('photo_id')
                ->nullable()
                ->constrained('ticket_photos')
                ->nullOnDelete();
        });

        // Pindahkan foto lama dari tabel tickets jika ada.
        $oldPhotos = DB::table('tickets')
            ->whereNotNull('photo_path')
            ->get(['id', 'photo_path']);

        foreach ($oldPhotos as $oldPhoto) {
            DB::table('ticket_photos')->insert([
                'ticket_id' => $oldPhoto->id,
                'photo_path' => $oldPhoto->photo_path,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('ticket_activities', function (Blueprint $table) {
            $table->dropConstrainedForeignId('photo_id');
        });

        Schema::dropIfExists('ticket_photos');
    }
};
