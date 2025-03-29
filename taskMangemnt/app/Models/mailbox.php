<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class mailbox extends Model
{
    /** @use HasFactory<\Database\Factories\MailboxFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id', 'email', 'service_type', 'access_token', 'refresh_token', 'expires_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
