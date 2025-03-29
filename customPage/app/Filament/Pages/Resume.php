<?php

namespace App\Filament\Pages;

use App\Models\Pages;
use App\Models\User;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;

class Resume extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;
    public User $user;


    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.resume';
    protected static ? string $title = 'resume page';


    public function mount(): void
    {
        $this->user = Filament::getCurrentPanel()->auth()->user();

        if($record = $this->user->pages->where('type', Pages::ABOUT)->first())
        {
            dd($record);
            $this->form->fill($record->data);
        }
    }
    public function form(Form $form): Form
    {
        return $form->schema([
            Repeater::make('Education')
                ->schema([
                    DatePicker::make('date of joining')->native()->required(),
                    DatePicker::make('date of living'),
                    TextInput::make('cores'),
                    TextInput::make('Institution'),
                ])->required()
                ->columns(2)
        ])->statePath('data');
    }
    public function getFormActions(): array
    {
        return [
            Action::make('save')
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),

        ];
    }
    public function seve()
    {
        Action::make('save')
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
            ->submit('save');
    }
}
