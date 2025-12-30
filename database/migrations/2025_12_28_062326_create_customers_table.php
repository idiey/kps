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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company_name')->nullable();
            $table->string('ic_number')->nullable(); // Malaysian IC number
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postcode')->nullable();
            $table->string('customer_type')->default('individual'); // individual, government, corporate
            $table->string('department')->nullable(); // For government customers
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('email');
            $table->index('phone');
            $table->index('ic_number');
            $table->index('customer_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
