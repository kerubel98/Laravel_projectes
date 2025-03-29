<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'unit_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the unit the user belongs to
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the roles assigned to the user
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Get the tasks assigned to the user
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    /**
     * Get the tasks created by the user
     */
    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    /**
     * Get the units managed by the user
     */
    public function managedUnits(): HasMany
    {
        return $this->hasMany(Unit::class, 'manager_id');
    }

    /**
     * Get the department directed by the user
     */
    public function directedDepartment(): HasMany
    {
        return $this->hasMany(Department::class, 'director_id');
    }

    /**
     * Get the KPIs assigned to the user
     */
    public function kpis(): HasMany
    {
        return $this->hasMany(KPI::class, 'assigned_to');
    }

    /**
     * Get the reports created by the user
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'created_by');
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($role): bool
    {
        return $this->roles->contains('name', $role);
    }

    /**
     * Check if user is a manager
     */
    public function isManager(): bool
    {
        return $this->hasRole('manager') || $this->managedUnits()->exists();
    }

    /**
     * Check if user is a director
     */
    public function isDirector(): bool
    {
        return $this->hasRole('director') || $this->directedDepartment()->exists();
    }

    /**
     * Get user's full permissions
     */
    public function permissions(): array
    {
        return $this->roles->flatMap->permissions->unique()->toArray();
    }
}
