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
        Schema::create('job_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workshop_job_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('from_status')->nullable(); // null for initial status
            $table->string('to_status');
            $table->text('notes')->nullable();
            $table->timestamp('changed_at');
            $table->timestamps();

            // Indexes
            $table->index('workshop_job_id');
            $table->index('user_id');
            $table->index('from_status');
            $table->index('to_status');
            $table->index('changed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_status_histories');
    }
};
