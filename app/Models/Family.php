<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubscriptionPlan;

class Family extends Model
{
    protected $table = 'family'; 
    protected $primaryKey = 'family_code';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'status', 
        'address', 
        'family_code',
        'phone',
        'subscription'
    ];

    public function parents()
    {
        return $this->hasMany(Parents::class, 'family_code', 'family_code');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'family_code', 'family_code');
    }
    
    /**
     * Get the family name from the primary parent
     */
    public function getFamilyNameAttribute()
    {
        $primaryParent = $this->parents()->first();
        if ($primaryParent) {
            return $primaryParent->fname . ' ' . $primaryParent->lname;
        }
        return 'Unknown Family';
    }
    
    /**
     * Retrieve the subscription plan associated with this family.
     * This assumes that the 'subscription' field in the family table stores
     * the plan name (e.g., "standard", "premium") matching SubscriptionPlan::name.
     *
     * @return \App\Models\SubscriptionPlan|null
     */
    public function subscriptionPlan()
    {
        return SubscriptionPlan::where('name', $this->subscription)->first();
    }
    
    /**
     * Calculate the total billing amount for the family.
     *
     * Billing Logic Example:
     * - First student is charged the full base amount.
     * - Each additional student is charged: base_amount * additional_multiplier.
     *
     * @return float
     */
    public function calculateBillingAmount()
    {
        $plan = $this->subscriptionPlan();
        if (!$plan) {
             return 0;
        }

        $studentCount = $this->students()->count();
        if ($studentCount < 1) {
            return 0;
        }

        $baseAmount = $plan->base_amount;
        $multiplier = $plan->additional_multiplier;

        // Billing Calculation:
        // Total = base amount for first student + (each additional student at: base amount * multiplier)
        $totalAmount = $baseAmount;
        if ($studentCount > 1) {
            $totalAmount += ($studentCount - 1) * ($baseAmount * $multiplier);
        }

        return $totalAmount;
    }
}