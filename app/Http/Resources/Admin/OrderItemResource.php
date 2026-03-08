<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'product_id' => $this->product_id,
            'product_name' => $this->product?->name,
            'product_image' => $this->product?->image_url,
            'quantity' => $this->quantity,
            'wholesale_price' => $this->wholesale_price,
            'customer_price' => $this->customer_price,
            'profit_per_item' => $this->profit_per_item,
            'subtotal' => $this->subtotal,
            'profit_subtotal' => $this->profit_subtotal,
        ];
    }
}
