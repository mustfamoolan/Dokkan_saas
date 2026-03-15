<?php

namespace App\Models;

use App\Traits\HasStoreScope;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasStoreScope;

    protected $fillable = [
        'store_id',
        'cashbox_id',
        'category',
        'amount',
        'expense_date',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function cashbox()
    {
        return $this->belongsTo(Cashbox::class);
    }

    public function transaction()
    {
        return $this->morphOne(CashboxTransaction::class, 'reference');
    }
}
