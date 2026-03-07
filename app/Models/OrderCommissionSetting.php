<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCommissionSetting extends Model
{
    protected $fillable = [
        'commission_value',
        'is_active',
    ];

    protected $casts = [
        'commission_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
