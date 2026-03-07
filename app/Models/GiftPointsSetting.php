<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftPointsSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'points_per_order',
        'is_active',
    ];

    protected $casts = [
        'points_per_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Scope for active settings.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
