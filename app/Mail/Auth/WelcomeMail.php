<?php

namespace App\Mail\Auth;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'أهلاً بك في ' . config('app.name', 'Car Rental') . ' - تم إنشاء حسابك بنجاح! ',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auth.welcome',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
