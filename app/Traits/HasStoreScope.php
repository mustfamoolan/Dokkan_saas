<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait HasStoreScope
{
    protected static function bootHasStoreScope()
    {
        static::creating(function ($model) {
            if (Auth::guard('subscriber')->check()) {
                $model->store_id = Auth::guard('subscriber')->user()->store->id;
            }
        });

        static::addGlobalScope('store', function (Builder $builder) {
            if (Auth::guard('subscriber')->check()) {
                $builder->where('store_id', Auth::guard('subscriber')->user()->store->id);
            }
        });
    }
}
