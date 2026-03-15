<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'sku'            => $this->sku,
            'barcode'        => $this->barcode,
            'category'       => $this->category ? [
                'id'         => $this->category->id,
                'name'       => $this->category->name,
            ] : null,
            'purchase_price' => (float) $this->purchase_price,
            'sale_price'     => (float) $this->sale_price,
            'is_active'      => (bool) $this->is_active,
            'stock'          => $this->whenLoaded('stocks', function() {
                return $this->stocks->sum('current_quantity');
            }),
            'created_at'     => $this->created_at->toDateTimeString(),
        ];
    }
}
