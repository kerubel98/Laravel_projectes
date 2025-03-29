<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    protected $fillable = [
        'user_id', 'zoho_message_id', 'subject',
        'sender', 'content', 'received_at',
        'thread_id', 'flag_id', 'priority', 'to_address',
        'cc_address', 'recipient_type'

    ];
    protected $casts = [
        'received_at' => 'datetime',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
