<?php

namespace App\Models;

use App\Traits\HasStoreScope;
use Illuminate\Database\Eloquent\Model;

class CashboxTransaction extends Model
{
    use HasStoreScope;

    protected $fillable = [
        'store_id',
        'cashbox_id',
        'amount',
        'direction',
        'type',
        'reference_type',
        'reference_id',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function cashbox()
    {
        return $this->belongsTo(Cashbox::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }
}
