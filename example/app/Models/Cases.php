<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cases extends Model
{
    /** @use HasFactory<\Database\Factories\CasesFactory> */
    use HasFactory;

    public function task(): BelongsToMany
    {
        return $this->belongsToMany(Task::class);
    }
}
