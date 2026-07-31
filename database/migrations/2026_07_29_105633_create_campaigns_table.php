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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertiser_id')->constrained('advertisers')->cascadeOnDelete();
            $table->string('name');
            $table->text('objective')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'active', 'completed', 'cancelled'])->default('draft');
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->decimal('budget', 16, 4)->default(0.00);
            $table->decimal('spent', 16, 4)->default(0.00);
            $table->json('targeting')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
