<?php
namespace App\Mail\Auth;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $loginTime,
        public ?string $ipAddress = null,
        public ?string $userAgent = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تنبيه أمني: تسجيل دخول جديد لحسابك',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auth.login-alert',
        );
    }
}
