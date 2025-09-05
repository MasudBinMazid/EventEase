<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sender_id',
        'title',
        'message',
        'type',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship with the user who receives the notification
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship with the admin/user who sent the notification
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Scope for unread notifications
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // Scope for read notifications
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    // Scope for notifications by type
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Mark notification as read
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    // Check if notification is unread
    public function isUnread()
    {
        return !$this->is_read;
    }
}
