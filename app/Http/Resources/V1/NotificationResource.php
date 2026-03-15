<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'type'       => $this->type,
            'title'      => $this->title,
            'message'    => $this->message,
            'severity'   => $this->severity,
            'is_read'    => (bool) $this->is_read,
            'action_url' => $this->action_url,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
