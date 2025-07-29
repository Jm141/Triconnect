<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'recipient_type', // all, teachers, parents
        'priority', // low, medium, high, urgent
        'sent_by',
        'status', // sent, read, archived
        'recipient_count'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the sender of the notification
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by', 'userCode');
    }

    /**
     * Get all recipients for this notification
     */
    public function recipients()
    {
        return $this->hasMany(NotificationRecipient::class);
    }

    /**
     * Get unread recipients for this notification
     */
    public function unreadRecipients()
    {
        return $this->hasMany(NotificationRecipient::class)->unread();
    }

    /**
     * Get read recipients for this notification
     */
    public function readRecipients()
    {
        return $this->hasMany(NotificationRecipient::class)->read();
    }

    /**
     * Get priority badge class
     */
    public function getPriorityBadgeClass()
    {
        switch ($this->priority) {
            case 'urgent':
                return 'badge-danger';
            case 'high':
                return 'badge-warning';
            case 'medium':
                return 'badge-info';
            case 'low':
                return 'badge-secondary';
            default:
                return 'badge-secondary';
        }
    }

    /**
     * Get recipient type display name
     */
    public function getRecipientTypeDisplay()
    {
        switch ($this->recipient_type) {
            case 'all':
                return 'All Teachers & Parents';
            case 'teachers':
                return 'Teachers Only';
            case 'parents':
                return 'Parents Only';
            default:
                return ucfirst($this->recipient_type);
        }
    }

    /**
     * Scope for active notifications
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'archived');
    }

    /**
     * Scope for urgent notifications
     */
    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }
} 