<?php

namespace App\Models;

use App\Traits\HasStoreScope;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use HasStoreScope;

    protected $fillable = [
        'store_id',
        'warehouse_id',
        'product_id',
        'opening_quantity',
        'current_quantity',
        'opening_cost',
        'alert_quantity',
    ];

    protected $casts = [
        'opening_quantity' => 'decimal:2',
        'current_quantity' => 'decimal:2',
        'opening_cost' => 'decimal:2',
        'alert_quantity' => 'decimal:2',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getStatusAttribute()
    {
        if ($this->alert_quantity && $this->current_quantity <= $this->alert_quantity) {
            return 'low';
        }
        return 'normal';
    }
}
