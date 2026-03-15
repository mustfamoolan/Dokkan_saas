<?php

namespace App\Models;

use App\Traits\HasStoreScope;
use Illuminate\Database\Eloquent\Model;

class SupplierPayment extends Model
{
    use HasStoreScope;

    protected $fillable = [
        'store_id',
        'supplier_id',
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

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
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
