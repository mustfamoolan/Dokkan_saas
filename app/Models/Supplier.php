<?php

namespace App\Models;

use App\Traits\HasStoreScope;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasStoreScope;

    protected $fillable = [
        'store_id',
        'name',
        'phone',
        'alternate_phone',
        'email',
        'address',
        'notes',
        'opening_balance',
        'opening_balance_type',
        'is_active',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
