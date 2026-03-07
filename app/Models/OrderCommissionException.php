<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCommissionException extends Model
{
    protected $fillable = [
        'user_id',
        'representative_id',
        'commission_value',
        'is_active',
    ];

    protected $casts = [
        'commission_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function representative()
    {
        return $this->belongsTo(Representative::class);
    }
}
