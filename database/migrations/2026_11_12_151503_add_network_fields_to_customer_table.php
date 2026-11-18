<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer', function (Blueprint $table) {
            $table->string('ip_address')->nullable();
            $table->string('vlan')->nullable();
            $table->string('pop_location')->nullable();
            $table->string('port_pon')->nullable();
            $table->string('cid')->nullable();
            $table->string('user_location')->nullable();
            $table->string('bandwidth')->nullable();
            $table->string('access_method')->nullable();
            $table->string('connection_type')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('customer', function (Blueprint $table) {
            $table->dropColumn([
                'ip_address',
                'vlan',
                'pop_location',
                'port_pon',
                'cid',
                'user_location',
                'bandwidth',
                'access_method',
                'connection_type',
            ]);
        });
    }
};