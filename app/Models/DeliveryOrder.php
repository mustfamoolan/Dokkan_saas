<?php

namespace App\Models;

use App\Traits\HasStoreScope;
use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    use HasStoreScope;

    protected $fillable = [
        'store_id',
        'customer_id',
        'sales_invoice_id',
        'representative_id',
        'order_number',
        'order_date',
        'status',
        'delivery_address',
        'notes',
        'assigned_at',
        'delivered_at',
    ];

    protected $casts = [
        'order_date' => 'date',
        'assigned_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function representative()
    {
        return $this->belongsTo(Representative::class);
    }

    public function invoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'sales_invoice_id');
    }
}
