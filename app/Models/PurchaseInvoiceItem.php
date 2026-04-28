<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id', 'product_id', 'cartons', 'units_per_carton',
        'purchase_price', 'cost_per_carton', 'retail_price', 'wholesale_price', 'is_gift'
    ];

    public function invoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'invoice_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
