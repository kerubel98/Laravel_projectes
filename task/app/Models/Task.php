<?php

namespace App\Models;

use App\Enum\TaskPriority;
use App\Enum\TaskStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = ['user_id', 'message_id', 'title', 'description'];
    protected $casts = [
        'status' => TaskStatus::class,
        'priority' => TaskPriority::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }


    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }
    public function issues():BelongsTo
    {
        return $this->belongsTo(Issues::class);
    }
}
