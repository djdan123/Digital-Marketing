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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertiser_id')->constrained('advertisers')->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->date('issued_at');
            $table->date('due_at')->nullable();
            $table->decimal('subtotal', 16, 4);
            $table->decimal('tax', 16, 4)->default(0.00);
            $table->decimal('total', 16, 4);
            $table->enum('status', ['draft', 'issued', 'paid', 'overdue', 'cancelled'])->default('draft');
            $table->json('line_items')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
