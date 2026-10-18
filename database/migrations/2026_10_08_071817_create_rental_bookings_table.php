<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $daysExpression = DB::connection()->getDriverName() === 'sqlite'
            ? "CAST(julianday(rental_end_date) - julianday(rental_start_date) AS INTEGER)"
            : 'DATEDIFF(rental_end_date, rental_start_date)';

        Schema::create('rental_bookings', function (Blueprint $table) use ($daysExpression) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles', 'id')->restrictOnDelete();
            $table->foreignId('customer_id')->constrained('customers', 'id')->restrictOnDelete();
            $table->date('rental_start_date');
            $table->date('rental_end_date');
            $table->text('pickup_location');
            $table->text('dropoff_location');
            $table->integer('initial_rental_days')->virtualAs($daysExpression);
            $table->decimal('rental_price_per_day', 8, 2);
            $table->decimal('initial_total_due_amount', 8, 2)->virtualAs('rental_price_per_day * initial_rental_days');
            $table->text('initial_check_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_bookings');
    }
};
