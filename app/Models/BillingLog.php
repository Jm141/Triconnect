<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingLog extends Model
{
    // Allow mass assignment for the relevant fields.
    protected $fillable = [
        'family_code',
        'child_id',
        'subscription_plan_id',
        'base_amount',
        'additional_multiplier',
        'amount_due',
    ];

    /**
     * Get the family associated with this billing log.
     */
    // public function family()
    // {
    //     return $this->belongsTo(Family::class);
    // }


    public function students()
    {
        return $this->hasMany(Student::class, 'family_code', 'family_code');
    }
    public function parents()
    {
        return $this->hasMany(Parents::class, 'family_code', 'family_code');
    }
    public function family()
    {
        return $this->belongsTo(Family::class, 'family_code', 'family_code');
    }
    /**
     * Get the child associated with this billing log.
     * Optional if billing is applied to a specific child.
     */
    public function child()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the subscription plan associated with this billing log.
     */
    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }
}