<?php

namespace App\Models;

use App\Traits\HasStoreScope;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;

class Representative extends User
{
    use HasStoreScope, Notifiable;

    protected $fillable = [
        'store_id',
        'name',
        'phone',
        'email',
        'password',
        'commission_type',
        'commission_value',
        'is_active',
        'notes',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'commission_value' => 'decimal:2',
            'password' => 'hashed',
        ];
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function orders()
    {
        return $this->hasMany(DeliveryOrder::class);
    }
}
