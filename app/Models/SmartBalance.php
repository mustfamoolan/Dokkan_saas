<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmartBalance extends Model
{
    protected $fillable = [
        'balancable_id',
        'balancable_type',
        'currency',
        'balance',
        'last_transaction_at'
    ];

    /**
     * Get the parent balancable model (User, Supplier, or Customer).
     */
    public function balancable()
    {
        return $this->morphTo();
    }
}
