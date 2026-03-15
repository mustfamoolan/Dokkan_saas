<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'monthly_price',
        'yearly_price',
        'currency',
        'is_free',
        'is_active',
        'is_visible',
        'is_default',
        'is_featured',
        'trial_days',
        'sort_order',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'is_active' => 'boolean',
        'is_visible' => 'boolean',
        'is_default' => 'boolean',
        'is_featured' => 'boolean',
        'monthly_price' => 'decimal:2',
        'yearly_price' => 'decimal:2',
    ];

    public function features()
    {
        return $this->hasMany(PlanFeature::class);
    }

    public function getFeatureValue($key, $default = null)
    {
        $feature = $this->features()->where('feature_key', $key)->first();
        
        if (!$feature) {
            return $default;
        }

        if ($feature->value_type === 'boolean') {
            return filter_var($feature->feature_value, FILTER_VALIDATE_BOOLEAN);
        }

        if ($feature->value_type === 'limit' || is_numeric($feature->feature_value)) {
            return (int) $feature->feature_value;
        }

        return $feature->feature_value;
    }
}
