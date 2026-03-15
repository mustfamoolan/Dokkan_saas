<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsageCounter extends Model
{
    protected $fillable = [
        'store_id',
        'counter_key',
        'current_value',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
