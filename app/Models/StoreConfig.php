<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreConfig extends Model
{
    protected $fillable = [
        'store_id',
        'default_warehouse_id',
        'default_cashbox_id',
        'default_walk_in_customer_id',
        'allow_sale_without_customer',
        'allow_negative_stock',
        'sales_prefix',
        'purchase_prefix',
        'customer_payment_prefix',
        'supplier_payment_prefix',
        'print_header_title',
        'show_logo_on_print',
        'show_phone_on_print',
        'show_address_on_print',
        'print_footer_note',
    ];

    protected $casts = [
        'allow_sale_without_customer' => 'boolean',
        'allow_negative_stock' => 'boolean',
        'show_logo_on_print' => 'boolean',
        'show_phone_on_print' => 'boolean',
        'show_address_on_print' => 'boolean',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function defaultWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'default_warehouse_id');
    }

    public function defaultCashbox()
    {
        return $this->belongsTo(Cashbox::class, 'default_cashbox_id');
    }

    public function defaultWalkInCustomer()
    {
        return $this->belongsTo(Customer::class, 'default_walk_in_customer_id');
    }
}
