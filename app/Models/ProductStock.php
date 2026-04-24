<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use HasFactory;

    protected $table = 'product_stock';

    protected $fillable = ['product_id', 'cartons', 'extra_units', 'total_units', 'low_stock_alert'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
