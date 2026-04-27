<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialTransaction extends Model
{
    protected $fillable = [
        'financial_box_id',
        'financial_category_id',
        'type',
        'amount',
        'related_type',
        'related_id',
        'description',
        'balance_after'
    ];

    public function box()
    {
        return $this->belongsTo(FinancialBox::class, 'financial_box_id');
    }

    public function category()
    {
        return $this->belongsTo(FinancialCategory::class, 'financial_category_id');
    }
}
