<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailClient extends Model
{
    /** @use HasFactory<\Database\Factories\EmailServicProviderFactory> */
    use HasFactory;
    protected $table = 'email_clients';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
