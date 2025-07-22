<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'base_amount',
        'additional_multiplier',
    ];

    /**
     * Get the families that are subscribed to this plan.
     */
    public function families()
    {
        return $this->hasMany(Family::class);
    }

    
    public function billingLogs()
    {
        return $this->hasMany(BillingLog::class);
    }
}