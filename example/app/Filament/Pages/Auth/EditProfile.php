<?php

namespace App\Filament\Pages\Auth;

use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseEditProfile;
use Symfony\Component\HttpKernel\Profiler\Profile;

class EditProfile extends BaseEditProfile implements HasActions
{
    protected static string $resource = Profile::class;
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                        TextInput::make('username')
                            ->required()
                            ->maxLength(255),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                    ])->extraAttributes(['class' => 'custom-form-container mb-5 w-full']);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getAuthenticateFormAction(),
            $this->getGoogleLoginAction(),
        ];
    }

    protected function getGoogleLoginAction(): Action
    {
        return Action::make('googleLogin')
            ->label('Login with Google')
            ->color('danger')
            ->extraAttributes(['class'=>'w-full'])
            ->url(route('google.login'));
    }

}
