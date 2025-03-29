<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mail extends Model
{
    /** @use HasFactory<\Database\Factories\MailFactory> */
    use HasFactory;

    protected $fillable = [
        'mail_account_id', 'message_id', 'subject',
        'body', 'from', 'to', 'cc', 'received_at', 'is_task'
    ];

    protected $casts = [
        'to' => 'array',
        'cc' => 'array',
        'received_at' => 'datetime',
        'is_task' => 'boolean'
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
