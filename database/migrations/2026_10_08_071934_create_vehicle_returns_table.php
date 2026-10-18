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
        Schema::create('vehicle_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_booking_id')->unique()->constrained('rental_bookings', 'id')->restrictOnDelete();
            // التواريخ والأيام
            $table->date('actual_return_date');
            $table->smallInteger('actual_rental_days');

            // المسافات
            $table->unsignedInteger('mileage'); // العداد الحالي للسيارة عند الإرجاع
            $table->unsignedInteger('consumed_mileage'); // المسافة المقطوعة في هذه الرحلة

            // الفحص والتكاليف
            $table->text('final_check_notes')->nullable();
            $table->decimal('additional_charges', 8, 2)->default(0); // غرامات، أضرار، تأخير
            $table->decimal('actual_total_due_amount', 10, 2); // الفاتورة النهائية
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_returns');
    }
};
