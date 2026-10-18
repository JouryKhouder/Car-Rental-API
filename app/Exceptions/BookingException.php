<?php

namespace App\Exceptions;

use Exception;

/**
 * أخطاء منطق الحجز المتوقعة (سيارة غير متاحة، تعارض حجز...)
 * الكنترولر بيحوّلها لرد JSON بالكود المناسب بدل 500.
 */
class BookingException extends Exception
{
    public function __construct(string $message, private int $status = 422)
    {
        parent::__construct($message);
    }

    public function status(): int
    {
        return $this->status;
    }
}