<?php

namespace App\Models;

use App\Traits\HasStoreScope;
use Illuminate\Database\Eloquent\Model;

class CustomerPayment extends Model
{
    use HasStoreScope;

    protected $fillable = [
        'store_id',
        'customer_id',
        'cashbox_id',
        'payment_number',
        'payment_date',
        'amount',
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function cashbox()
    {
        return $this->belongsTo(Cashbox::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function transaction()
    {
        return $this->morphOne(CashboxTransaction::class, 'reference');
    }
}
