<?php

namespace App\Filament\Pages\Auth;



use Filament\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Pages\Page;
use \Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected function getFormActions(): array
    {
        return [
            $this->getAuthenticateFormAction()
                ->label('Sign Up')
                ->extraAttributes(['class' => 'w-full']),
            Action::make('divider')
                -> View('filament.components.divider')
                ->extraAttributes(['class' => 'w-full cursor-default'])
                ->disabled(),
            $this->getGoogleLoginAction()
                ->extraAttributes(['class' => 'w-full']),
        ];
    }

    protected function hasFullWidthFormActions(): bool
    {
        return false;
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->revealable(false);
    }
    protected function getGoogleLoginAction(): Action
    {
        return Action::make('Zoho')
            ->label('Login with Zoho')
//            -> View('filament.components.divider')
            ->color('danger') // You can customize the color
            ->icon('heroicon-o-document-text') // Assuming you have FontAwesome icons available
            ->url(route('zoho.login'));
    }


}
