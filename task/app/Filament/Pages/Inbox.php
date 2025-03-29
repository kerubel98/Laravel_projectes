<?php

namespace App\Filament\Pages;

use App\Enum\TaskPriority;
use App\Enum\TaskStatus;
use App\Models\Message;
use App\Models\Task;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class Inbox extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $view = 'filament.pages.inbox';
    protected static ?string $navigationIcon = 'heroicon-o-inbox';
    protected static ?string $navigationLabel = 'Email Inbox';
    public function getMaxContentWidth(): \Filament\Support\Enums\MaxWidth|null|string
    {
        return MaxWidth::Full;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(auth()->user()->messages()->getQuery())
            ->columns([
                TextColumn::make('subject')
                    ->searchable()
                    ->wrap()
                    ->sortable()
                    ->description(fn (Message $record) =>
                        Str::limit($record->content, 150) . "\n" .
                        'From: ' . Str::limit($record->sender, 50)
                    )
                    ->extraAttributes(['class' => 'whitespace-pre-wrap font-medium']),
            ])
            ->filters([
                SelectFilter::make('priority')
                    ->options([
                        TaskPriority::HIGH->value => 'High Priority',
                        TaskPriority::MEDIUM->value => 'Medium Priority',
                        TaskPriority::LOW->value => 'Normal',
                    ]),
                SelectFilter::make('flag_id')
                    ->options([
                        'important' => 'Important',
                        'info' => 'Info',
                        'flag_not_set' => 'No Flag',
                    ]),
                SelectFilter::make('recipient_type')
                    ->label('Addressed me as')
                    ->options([
                        'to' => 'To me',
                        'cc' => 'CC'
                    ])
            ])
            ->actions([
                ViewAction::make()
                    ->modalHeading(fn (Message $record) => $record->subject)
                    ->modalContent(fn (Message $record) => view('filament.actions.email-details', ['record' => $record]))
                    ->modalFooterActions([
                        Action::make('createTask')
                            ->label('Create Task')
                            ->color('primary')
                            ->icon('heroicon-o-document-plus')
                            ->form([
                                Hidden::make('message_id')
                                    ->default(fn (Message $record) => $record->id),
                                TextInput::make('title')
                                    ->default(fn (Message $record) => $record->subject)
                                    ->required(),
                                Textarea::make('description')
                                    ->default(fn (Message $record) => Str::limit($record->content, 500))
                                    ->required()
                                    ->rows(6)
                                    ->autosize()
                                    ->extraAttributes(['class' => 'w-full min-h-[200px]']),
                            ])
                            ->action(function (array $data) {
                                try {
                                    Task::create([
                                        'user_id' => auth()->id(),
                                        'message_id' => $data['message_id'],
                                        'title' => $data['title'],
                                        'description' => $data['description']
                                    ]);

                                    Notification::make()
                                        ->title('Task created successfully')
                                        ->success()
                                        ->send();

                                } catch (\Exception $e) {
                                    Notification::make()
                                        ->title('Error creating task')
                                        ->body($e->getMessage())
                                        ->danger()
                                        ->send();

                                    logger()->error('Task creation failed: ' . $e->getMessage());
                                }
                            })
                    ])
            ])
            ->defaultSort('received_at', 'desc');

    }
}
