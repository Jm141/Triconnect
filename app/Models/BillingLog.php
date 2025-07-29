<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BillingLog extends Model
{
    // Allow mass assignment for the relevant fields.
    protected $fillable = [
        'family_code',
        'child_id',
        'subscription_plan_id',
        'subscription_plan',
        'base_amount',
        'additional_multiplier',
        'amount_due',
        'status',
        'billing_date',
        'due_date',
        'paid_date',
        'payment_method',
        'student_count',
        'notes'
    ];

    protected $casts = [
        'billing_date' => 'datetime',
        'due_date' => 'datetime',
        'paid_date' => 'datetime',
        'base_amount' => 'decimal:2',
        'additional_multiplier' => 'decimal:2',
        'amount_due' => 'decimal:2',
    ];

    /**
     * Get the family associated with this billing log.
     */
    public function family()
    {
        return $this->belongsTo(Family::class, 'family_code', 'family_code');
    }

    /**
     * Get the students associated with this billing log.
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'family_code', 'family_code');
    }

    /**
     * Get the parents associated with this billing log.
     */
    public function parents()
    {
        return $this->hasMany(Parents::class, 'family_code', 'family_code');
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

    /**
     * Check if billing is overdue
     */
    public function isOverdue()
    {
        return $this->status === 'pending' && $this->due_date && $this->due_date->isPast();
    }

    /**
     * Get overdue days
     */
    public function getOverdueDays()
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        return $this->due_date->diffInDays(Carbon::now());
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass()
    {
        switch ($this->status) {
            case 'paid':
                return 'badge-success';
            case 'pending':
                return $this->isOverdue() ? 'badge-danger' : 'badge-warning';
            case 'cancelled':
                return 'badge-secondary';
            default:
                return 'badge-info';
        }
    }

    /**
     * Get formatted amount due
     */
    public function getFormattedAmountDue()
    {
        return '₱' . number_format($this->amount_due, 2);
    }

    /**
     * Get formatted billing date
     */
    public function getFormattedBillingDate()
    {
        return $this->billing_date ? $this->billing_date->format('M j, Y') : 'N/A';
    }

    /**
     * Get formatted due date
     */
    public function getFormattedDueDate()
    {
        return $this->due_date ? $this->due_date->format('M j, Y') : 'N/A';
    }

    /**
     * Get formatted paid date
     */
    public function getFormattedPaidDate()
    {
        return $this->paid_date ? $this->paid_date->format('M j, Y') : 'N/A';
    }

    /**
     * Scope for pending billing
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for paid billing
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope for overdue billing
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
                    ->where('due_date', '<', Carbon::now());
    }

    /**
     * Scope for this month's billing
     */
    public function scopeThisMonth($query)
    {
        return $query->whereYear('created_at', Carbon::now()->year)
                    ->whereMonth('created_at', Carbon::now()->month);
    }
}