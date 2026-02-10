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
        Schema::create('monthly_deductions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('peneroka_id')->constrained('penerokas')->onDelete('cascade');
            $table->foreignUuid('site_id')->constrained('sites')->onDelete('cascade');
            $table->date('month');
            $table->decimal('amount', 12, 2)->default(0);
            $table->decimal('unallocated_amount', 12, 2)->default(0);
            $table->boolean('is_closed')->default(false);
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->unique(['peneroka_id', 'month']);
            $table->index(['site_id', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_deductions');
    }
};
