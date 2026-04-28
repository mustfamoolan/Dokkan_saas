<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id', 'invoice_number', 'real_invoice_number', 'invoice_date',
        'driver_cost', 'workers_cost', 'costs_on_supplier', 'total_amount', 'discount'
    ];

    public function items()
    {
        return $this->hasMany(PurchaseInvoiceItem::class, 'invoice_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
