<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer', function (Blueprint $table) {
            $table->renameColumn('full_name', 'pic');
        });

        DB::statement("
            UPDATE customer
            SET cid = 'CID-' || LPAD(id::text, 5, '0')
            WHERE cid IS NULL OR TRIM(cid) = ''
        ");

        Schema::table('customer', function (Blueprint $table) {
            $table->unique('cid', 'customer_cid_unique');
        });
    }

    public function down(): void
    {
        Schema::table('customer', function (Blueprint $table) {
            $table->dropUnique('customer_cid_unique');
            $table->renameColumn('pic', 'full_name');
        });
    }
};