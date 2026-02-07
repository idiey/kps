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
        Schema::create('workshop_user', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('workshop_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['supervisor', 'technician', 'staff'])->default('staff');
            $table->timestamps();

            // Unique constraint: user can only be assigned once per workshop
            $table->unique(['workshop_id', 'user_id']);
            
            // Indexes for performance
            $table->index('workshop_id');
            $table->index('user_id');
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workshop_user');
    }
};
