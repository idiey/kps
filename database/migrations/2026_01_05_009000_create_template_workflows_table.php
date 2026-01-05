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
        Schema::create('template_workflows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('job_templates')->onDelete('cascade');
            $table->foreignId('workflow_id')->constrained('workflows')->onDelete('cascade');
            $table->boolean('is_default')->default(true);
            $table->timestamps();

            $table->unique(['template_id', 'workflow_id'], 'unique_template_workflow');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_workflows');
    }
};
