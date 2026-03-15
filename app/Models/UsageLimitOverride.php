<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsageLimitOverride extends Model
{
    protected $fillable = [
        'store_id',
        'feature_key',
        'override_value',
        'value_type',
        'notes',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
