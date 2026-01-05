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
        Schema::create('job_field_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('workshop_jobs')->onDelete('cascade');
            $table->foreignId('field_id')->constrained('template_fields')->onDelete('cascade');

            // Store values in appropriate column based on type
            $table->text('value_text')->nullable();
            $table->decimal('value_number', 20, 4)->nullable();
            $table->date('value_date')->nullable();
            $table->timestamp('value_datetime')->nullable();
            $table->boolean('value_boolean')->nullable();
            $table->json('value_json')->nullable();

            $table->timestamps();

            $table->unique(['job_id', 'field_id'], 'unique_job_field');
            $table->index('job_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_field_values');
    }
};
