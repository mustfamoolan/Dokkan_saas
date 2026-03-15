<?php

namespace App\Models;

use App\Traits\HasStoreScope;
use Illuminate\Database\Eloquent\Model;

class Representative extends Model
{
    use HasStoreScope;

    protected $fillable = [
        'store_id',
        'name',
        'phone',
        'email',
        'password',
        'commission_type',
        'commission_value',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'commission_value' => 'decimal:2',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function orders()
    {
        return $this->hasMany(DeliveryOrder::class);
    }
}
