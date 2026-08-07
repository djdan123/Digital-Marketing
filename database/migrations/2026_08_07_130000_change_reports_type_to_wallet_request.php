<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('reports')) {
            return;
        }

        DB::statement("ALTER TABLE reports MODIFY COLUMN type ENUM('campaign', 'media', 'payment', 'performance', 'wallet_request') NOT NULL DEFAULT 'campaign'");
        DB::table('reports')->where('type', 'recharge_request')->update(['type' => 'wallet_request']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('reports')) {
            return;
        }

        DB::table('reports')->where('type', 'wallet_request')->update(['type' => 'recharge_request']);
        DB::statement("ALTER TABLE reports MODIFY COLUMN type ENUM('campaign', 'media', 'payment', 'performance', 'recharge_request') NOT NULL DEFAULT 'campaign'");
    }
};