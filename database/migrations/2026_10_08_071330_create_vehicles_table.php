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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->unsignedBigInteger('kilo_meterage');
            $table->string('plate_number')->unique();
            $table->enum('status', ['available', 'rented', 'maintenance'])->default('available');
            $table->decimal('rental_price_per_day', 8, 2);
            $table->string('color');
            $table->unsignedTinyInteger('seats')->default(5);
            $table->string('transmission')->default('automatic');
            $table->foreignId('fuel_type_id')->constrained('fuel_types', 'id')->restrictOnDelete();
            $table->foreignId('car_category_id')->constrained('car_categories', 'id')->restrictOnDelete();
            $table->foreignId('model_id')->constrained('car_models', 'id')->restrictOnDelete();
            $table->foreignId('drive_type_id')->constrained('drive_types', 'id')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
