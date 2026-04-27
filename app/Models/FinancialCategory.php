<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialCategory extends Model
{
    protected $fillable = ['name', 'type', 'slug'];

    public function transactions()
    {
        return $this->hasMany(FinancialTransaction::class);
    }
}
