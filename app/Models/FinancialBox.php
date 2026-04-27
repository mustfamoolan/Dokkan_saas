<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialBox extends Model
{
    protected $fillable = ['name', 'balance', 'currency', 'type', 'is_default'];

    public function transactions()
    {
        return $this->hasMany(FinancialTransaction::class);
    }
}
