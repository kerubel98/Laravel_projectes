<?php

namespace App\Filament\Widgets;

use App\Enum\TaskStatus;
use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PendingTask extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pending Tasks', Task::query()->where('status', 'todo')->count()),
            Stat::make('Completed Tasks', Task::query()->where('status', TaskStatus::DONE->value)->count()),
            Stat::make('In Progress', Task::query()->where('status', TaskStatus::IN_PROGRESS->value)->count())
        ];
    }
}
