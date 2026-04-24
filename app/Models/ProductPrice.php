<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'cost', 'retail_price', 'wholesale_price', 'additional_costs'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
