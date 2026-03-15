<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Admin;

class Payment extends Model
{
    protected $fillable = [
        'subscription_id',
        'subscriber_id',
        'store_id',
        'amount',
        'currency',
        'payment_method',
        'status',
        'reference_number',
        'notes',
        'reviewed_by_admin_id',
        'reviewed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function receipt()
    {
        return $this->hasOne(PaymentReceipt::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by_admin_id');
    }
}
