<?php

namespace App\Http\Resources\Representatives;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawalRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'representative' => $this->representative->name,
            'representative_id' => $this->representative_id,
            'amount' => $this->amount,
            'bank' => $this->bank_name,
            'account_number' => $this->account_number,
            'status' => $this->status->getLabel(),
            'status_key' => $this->status->value,
            'requested_at' => $this->requested_at?->format('Y-m-d H:i'),
            'notes' => $this->notes,
            'rejected_reason' => $this->rejected_reason,
            'is_direct_withdrawal' => $this->is_direct_withdrawal,
        ];
    }
}
