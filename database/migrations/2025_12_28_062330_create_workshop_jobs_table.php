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
        Schema::create('workshop_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_number')->unique(); // Auto-generated unique job reference
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('new'); // new, in_progress, completed, invoiced
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->string('vehicle_registration')->nullable(); // For vehicle repairs
            $table->string('asset_tag')->nullable(); // For asset/equipment repairs
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->decimal('actual_cost', 10, 2)->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('invoiced_at')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance and filtering
            $table->index('job_number');
            $table->index('status');
            $table->index('priority');
            $table->index('customer_id');
            $table->index('assigned_to');
            $table->index('created_at');
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workshop_jobs');
    }
};
