<?php

namespace App\Filament\Resources;

use App\Enum\TaskPriority;
use App\Enum\TaskStatus;
use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(auth()->id())
                    ->required(),
                Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->label('status')
                    ->options([
                       TaskStatus::TODO->value => TaskStatus::TODO->value,
                        TaskStatus::IN_PROGRESS->value => TaskStatus::IN_PROGRESS->value,
                        TaskStatus::DONE->value => TaskStatus::DONE->value,
                    ]),
                Forms\Components\Select::make('priority')
                    ->options([
                        TaskPriority::HIGH->value => TaskPriority::HIGH->value,
                        TaskPriority::LOW->value => TaskPriority::LOW->value,
                        TaskPriority::MEDIUM->value => TaskPriority::MEDIUM->value,
                    ]),
                Forms\Components\DatePicker::make('due_date')->label('Due Date'),
                Forms\Components\Textarea::make('description')->columnSpan('full')
                ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                    Tables\Columns\TextColumn::make('title')
                        ->searchable()
                        ->sortable()
                        ->extraAttributes(['class' => 'text-primary']),
                    Tables\Columns\TextColumn::make('status')
                        ->searchable()
                        ->sortable(),
                    Tables\Columns\TextColumn::make('priority')
                        ->searchable()->sortable(),
                    Tables\Columns\TextColumn::make('deadline')
                        ->searchable()
                        ->sortable(),
            ])
            ->filters([
                SelectFilter::make('priority')
                    ->options(TaskPriority::values()),
                SelectFilter::make('status')
                        ->options(TaskStatus::values()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\IssuesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
