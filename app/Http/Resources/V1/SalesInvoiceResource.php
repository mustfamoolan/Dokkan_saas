<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesInvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'invoice_number' => $this->invoice_number,
            'customer'       => new CustomerResource($this->whenLoaded('customer')),
            'warehouse'      => new WarehouseResource($this->whenLoaded('warehouse')),
            'invoice_date'   => $this->invoice_date->toDateString(),
            'status'         => $this->status,
            'subtotal'       => (float) $this->subtotal,
            'discount'       => (float) $this->discount_amount,
            'total_amount'   => (float) $this->total_amount,
            'items'          => $this->whenLoaded('items', function() {
                return $this->items->map(function($item) {
                    return [
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'quantity' => (float) $item->quantity,
                        'unit_price' => (float) $item->unit_price,
                        'total' => (float) $item->line_total,
                    ];
                });
            }),
            'created_at'     => $this->created_at->toDateTimeString(),
        ];
    }
}
