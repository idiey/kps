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
        Schema::create('kew_pa_10s', function (Blueprint $table) {
            $table->id();
            $table->string('kew_pa_10_number')->unique();
            $table->foreignId('government_department_id')->constrained()->cascadeOnDelete();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->text('description');
            $table->string('priority')->default('medium');
            $table->string('budget_allocation_reference')->nullable();
            $table->string('kew_pa_10_document_path')->nullable();
            $table->boolean('form_completeness_verified')->default(false);
            $table->boolean('signatures_verified')->default(false);
            $table->text('verification_notes')->nullable();
            $table->date('received_date');
            $table->foreignId('received_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->index('kew_pa_10_number');
            $table->index('government_department_id');
            $table->index('received_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kew_pa_10s');
    }
};
