<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
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
            'title' => $this->title,
            'amount' => (float) $this->amount,
            'category' => $this->category ?? 'عام',
            'notes' => $this->notes,
            'expense_date' => $this->expense_date->format('Y-m-d'),
            'creator_name' => $this->creator->name ?? 'غير معروف',
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
