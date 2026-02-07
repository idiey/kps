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
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->string('part_number')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category');
            $table->integer('quantity_in_stock')->default(0);
            $table->integer('minimum_stock_level')->default(10);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->string('unit_of_measurement')->default('piece');
            $table->string('location')->nullable();
            $table->timestamps();

            $table->index('part_number');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parts');
    }
};
