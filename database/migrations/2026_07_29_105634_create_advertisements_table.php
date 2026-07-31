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
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->cascadeOnDelete();
            $table->foreignId('media_id')->constrained('media')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('format', ['audio', 'video', 'image', 'text'])->default('text');
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'scheduled', 'completed'])->default('draft');
            $table->json('meta')->nullable();
            $table->decimal('cost', 16, 4)->default(0.00);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
