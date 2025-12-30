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
        Schema::create('job_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workshop_job_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->boolean('is_public')->default(false); // false = private/internal, true = visible to customer
            $table->string('note_type')->default('general'); // general, diagnostic, repair, inspection
            $table->json('attachments')->nullable(); // Array of file paths/URLs
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('workshop_job_id');
            $table->index('user_id');
            $table->index('is_public');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_notes');
    }
};
