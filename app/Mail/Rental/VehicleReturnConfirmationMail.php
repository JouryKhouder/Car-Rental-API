<?php

namespace App\Mail\Rental;

use App\Models\RentalBooking;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VehicleReturnConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public RentalBooking $rental,
        public Vehicle $vehicle
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'إشعار إرجاع السيارة وإغلاق العقد #' . $this->rental->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.rentals.returned',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
