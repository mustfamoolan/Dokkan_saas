<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftPointsException extends Model
{
    use HasFactory;

    protected $fillable = [
        'representative_id',
        'user_id',
        'points_per_order',
        'is_active',
    ];

    protected $casts = [
        'points_per_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the representative associated with this exception.
     */
    public function representative()
    {
        return $this->belongsTo(Representative::class);
    }

    /**
     * Get the user associated with this exception.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for active exceptions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get active exception for a specific representative.
     */
    public static function getForRepresentative(int $representativeId)
    {
        return self::active()->where('representative_id', $representativeId)->first();
    }

    /**
     * Get active exception for a specific user.
     */
    public static function getForUser(int $userId)
    {
        return self::active()->where('user_id', $userId)->first();
    }
}
