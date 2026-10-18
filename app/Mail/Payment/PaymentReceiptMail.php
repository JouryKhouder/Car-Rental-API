<?php

namespace App\Mail\Payment;

use App\Models\RentalBooking;
use App\Models\RentalTransaction;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $paymentTypeLabel;

    public function __construct(
        public User $user,
        public ?RentalTransaction $payment,
        public RentalBooking $rental,
        public Vehicle $vehicle,
        public ?float $remainingBalance = 0.0
    ) {
        $this->paymentTypeLabel = match ($this->payment->type ?? 'deposit') {
            'deposit'    => 'دفعة أولى مقدمة (Deposit)',
            'final_payment'      => 'الدفعة المتبقية / الختامية',
            'refund'    => 'رسوم تأخير / غرامة',
            default      => 'دفعة مالية',
        };
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'إيصال دفع #' . ($this->payment->transaction_id ?? $this->payment->id) . ' - ' . config('app.name', 'Car Rental'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payments.receipt',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
