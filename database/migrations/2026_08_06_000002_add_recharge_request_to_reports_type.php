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

        DB::statement("ALTER TABLE reports MODIFY COLUMN type ENUM('campaign', 'media', 'payment', 'performance', 'recharge_request') NOT NULL DEFAULT 'campaign'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('reports')) {
            return;
        }

        DB::statement("ALTER TABLE reports MODIFY COLUMN type ENUM('campaign', 'media', 'payment', 'performance') NOT NULL DEFAULT 'campaign'");
    }
};
