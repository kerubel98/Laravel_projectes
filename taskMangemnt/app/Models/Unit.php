<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'department_id',
        'manager_id'
    ];

    /**
     * Get the department that owns the unit
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get all users belonging to this unit
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the manager of the unit
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get all managers in the unit (including the primary manager)
     */
    public function managers(): HasMany
    {
        return $this->hasMany(User::class)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'manager');
            });
    }

    /**
     * Get all tasks assigned to this unit
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get all KPIs assigned to this unit
     */
    public function kpis(): HasMany
    {
        return $this->hasMany(KPI::class);
    }

    /**
     * Get the unit's performance statistics
     */
    public function performance(): array
    {
        return [
            'total_users' => $this->users()->count(),
            'active_tasks' => $this->tasks()->where('status', '!=', 'completed')->count(),
            'completed_tasks' => $this->tasks()->where('status', 'completed')->count(),
            'kpi_completion_rate' => $this->kpis()->avg('completion_rate'),
        ];
    }
}
