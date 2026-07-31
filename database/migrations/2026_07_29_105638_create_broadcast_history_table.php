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
        Schema::create('broadcast_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('broadcast_id')->constrained('broadcasts')->cascadeOnDelete();
            $table->foreignId('schedule_id')->constrained('schedules')->cascadeOnDelete();
            $table->enum('status', ['scheduled', 'completed', 'failed', 'cancelled']);
            $table->text('notes')->nullable();
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('broadcast_history');
    }
};
