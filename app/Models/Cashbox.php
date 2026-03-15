<?php

namespace App\Models;

use App\Traits\HasStoreScope;
use Illuminate\Database\Eloquent\Model;

class Cashbox extends Model
{
    use HasStoreScope;

    protected $fillable = [
        'store_id',
        'name',
        'current_balance',
        'is_active',
    ];

    protected $casts = [
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function transactions()
    {
        return $this->hasMany(CashboxTransaction::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
