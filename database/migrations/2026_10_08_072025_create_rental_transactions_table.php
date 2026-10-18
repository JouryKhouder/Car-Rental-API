<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rental_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('rental_bookings', 'id')->restrictOnDelete();
            $table->text('payment_method');
            $table->enum('payment_type', ['deposit', 'final_payment', 'refund']);
            $table->decimal('amount', 8, 2);
            $table->date('transaction_date');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_transactions');
    }
};
