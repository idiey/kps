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
        Schema::create('workflow_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained('workflows')->onDelete('cascade');
            $table->foreignId('status_id')->nullable()->constrained('workflow_statuses')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('rule_type', 50); // 'field_required', 'field_visible', 'auto_assign', 'notification'

            // Conditions (when to apply this rule)
            $table->json('conditions')->nullable();

            // Actions (what to do)
            $table->json('actions')->nullable();

            $table->integer('priority')->default(0); // Execution order
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index(['workflow_id', 'status_id']);
            $table->index('rule_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_rules');
    }
};
