<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Mark message as read
     */
    public function markAsRead(): void
    {
        $this->update([
            'status' => 'read',
            'read_at' => now(),
        ]);
    }

    /**
     * Mark message as replied
     */
    public function markAsReplied(): void
    {
        $this->update([
            'status' => 'replied',
        ]);
    }

    /**
     * Scope: new messages
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Scope: unread messages
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
}