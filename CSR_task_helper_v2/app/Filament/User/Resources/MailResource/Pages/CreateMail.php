<?php

namespace App\Filament\User\Resources\MailResource\Pages;

use App\Filament\User\Resources\MailResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMail extends CreateRecord
{
    protected static string $resource = MailResource::class;
}
