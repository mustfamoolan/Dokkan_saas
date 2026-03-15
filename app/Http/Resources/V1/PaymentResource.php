<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'reference'      => $this->reference_number,
            'type'           => $this->payment_type,
            'amount'         => (float) $this->amount,
            'payment_date'   => $this->payment_date->toDateString(),
            'entity'         => $this->when($this->customer_id, [
                'type' => 'customer',
                'id' => $this->customer_id,
                'name' => $this->customer?->name,
            ], [
                'type' => 'supplier',
                'id' => $this->supplier_id,
                'name' => $this->supplier?->name,
            ]),
            'cashbox'        => $this->cashbox ? [
                'id' => $this->cashbox->id,
                'name' => $this->cashbox->name,
            ] : null,
            'notes'          => $this->notes,
            'created_at'     => $this->created_at->toDateTimeString(),
        ];
    }
}
