<?php

namespace UserNotifications\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $table = 'user_notifications';

    protected $fillable = [
        'title',
        'message',
        'sender_id',
        'receiver_id',
        'receiver_role',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'receiver_id');
    }

    public function scopeUnreadFor(Builder $query, $userId): Builder
    {
        return $query->where('receiver_id', $userId)->where('is_read', false);
    }

    public function markAsRead(): void
    {
        if ($this->is_read) {
            return;
        }

        $this->forceFill([
            'is_read' => true,
            'read_at' => now(),
        ])->save();
    }
}
