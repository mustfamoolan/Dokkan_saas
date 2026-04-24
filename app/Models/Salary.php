<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'month',
        'year',
        'paid_at',
        'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
