<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'subscriber_id',
        'name',
        'phone',
        'address',
        'logo',
        'currency',
        'locale',
        'timezone',
        'status',
    ];

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function counters()
    {
        return $this->hasMany(UsageCounter::class);
    }

    public function overrides()
    {
        return $this->hasMany(UsageLimitOverride::class);
    }

    public function config()
    {
        return $this->hasOne(StoreConfig::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }
}
