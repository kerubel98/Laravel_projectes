<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssueProgressUpdate extends Model
{
    /** @use HasFactory<\Database\Factories\IssueProgressUpdateFactory> */
    use HasFactory;

    protected $fillable = ['issue_id', 'user_id', 'update', 'progress_percentage'];

    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
