<?php

namespace App\Models;

use App\Traits\HasStoreScope;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasStoreScope;

    protected $fillable = [
        'store_id',
        'name',
        'code',
        'address',
        'notes',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    protected static function booted()
    {
        static::saving(function ($warehouse) {
            if ($warehouse->is_default) {
                // Ensure only one default warehouse per store
                static::where('store_id', $warehouse->store_id)
                    ->where('id', '!=', $warehouse->id)
                    ->update(['is_default' => false]);
            }
        });
    }
}
