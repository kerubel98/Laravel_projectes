<?php

namespace App\Filament\User\Resources\MailResource\Pages;

use App\Filament\User\Resources\MailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMail extends EditRecord
{
    protected static string $resource = MailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
