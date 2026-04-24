<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'location',
        'category',
        'status'
    ];

    public function balances()
    {
        return $this->morphMany(SmartBalance::class, 'balancable');
    }
}
