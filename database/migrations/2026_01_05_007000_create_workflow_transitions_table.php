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
        Schema::create('workflow_transitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained('workflows')->onDelete('cascade');
            $table->foreignId('from_status_id')->constrained('workflow_statuses')->onDelete('cascade');
            $table->foreignId('to_status_id')->constrained('workflow_statuses')->onDelete('cascade');
            $table->string('name')->nullable(); // Optional: 'Approve Inspection'
            $table->text('description')->nullable();

            // Permissions
            $table->string('requires_permission')->nullable(); // Spatie permission
            $table->json('allowed_roles')->nullable(); // Array of role IDs/names

            // Conditions (when can this transition execute)
            $table->json('conditions')->nullable();

            // Actions (what happens after transition)
            $table->json('actions')->nullable();

            // UI
            $table->string('button_label')->nullable();
            $table->string('button_color', 50)->nullable();
            $table->text('confirmation_message')->nullable();
            $table->boolean('requires_comment')->default(false);

            $table->json('metadata')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);

            $table->timestamps();

            $table->index('from_status_id');
            $table->index('to_status_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_transitions');
    }
};
