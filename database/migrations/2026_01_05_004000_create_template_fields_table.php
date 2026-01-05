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
        Schema::create('template_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('job_templates')->onDelete('cascade');
            $table->foreignId('field_type_id')->constrained('template_field_types');
            $table->string('name'); // Display name
            $table->string('code', 100); // Unique code within template
            $table->text('description')->nullable();
            $table->text('placeholder')->nullable();
            $table->text('default_value')->nullable();

            // Ordering & Layout
            $table->string('section')->nullable(); // Group fields
            $table->integer('display_order')->default(0);
            $table->integer('grid_column_span')->default(12); // 1-12 for grid system

            // Validation
            $table->boolean('is_required')->default(false);
            $table->json('validation_rules')->nullable();
            $table->json('conditional_rules')->nullable(); // Show/hide based on other fields

            // Options (for select, radio, checkbox)
            $table->json('options')->nullable();
            $table->string('options_source', 50)->nullable(); // 'static', 'database', 'api'
            $table->text('options_query')->nullable(); // For database/API source

            // Calculated Fields
            $table->text('formula')->nullable(); // For calculated fields
            $table->string('calculation_trigger', 50)->nullable(); // 'on_change', 'on_save'

            // Help & Hints
            $table->text('help_text')->nullable();
            $table->text('tooltip')->nullable();

            // Metadata
            $table->json('metadata')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['template_id', 'code'], 'unique_template_field');
            $table->index(['template_id', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_fields');
    }
};
