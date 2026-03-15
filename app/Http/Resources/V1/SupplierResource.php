<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'phone'        => $this->phone,
            'email'        => $this->email,
            'address'      => $this->address,
            'balance'      => (float) $this->balance,
            'is_active'    => (bool) $this->is_active,
            'created_at'   => $this->created_at->toDateTimeString(),
        ];
    }
}
