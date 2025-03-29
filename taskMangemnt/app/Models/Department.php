<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'director_id'
    ];

    /**
     * Get the director of the department
     */
    public function director(): BelongsTo
    {
        return $this->belongsTo(User::class, 'director_id');
    }

    /**
     * Get all units belonging to this department
     */
    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    /**
     * Get all users who are members of this department (through units)
     */
    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            Unit::class,
            'department_id', // Foreign key on units table
            'unit_id',       // Foreign key on users table
            'id',           // Local key on departments table
            'id'            // Local key on units table
        );
    }

    /**
     * Get all employees of the department (alias for users)
     */
    public function employees(): HasManyThrough
    {
        return $this->users();
    }

    /**
     * Get all managers in the department
     */
    public function managers(): HasMany
    {
        return $this->hasMany(User::class)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'manager');
            });
    }
}
