<?php

namespace App\Models;

use App\Traits\HasStoreScope;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasStoreScope;

    protected $fillable = [
        'store_id',
        'type',
        'title',
        'message',
        'severity',
        'is_read',
        'action_url',
        'meta',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'meta' => 'json',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
