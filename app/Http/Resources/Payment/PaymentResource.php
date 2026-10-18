<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'rental_id'      => $this->rental_id,
            'amount'         => (float) $this->amount,
            'type'           => $this->type,
            'payment_method' => $this->payment_method,
            'transaction_id' => $this->transaction_id,
            'status'         => $this->status,
            'paid_at'        => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
