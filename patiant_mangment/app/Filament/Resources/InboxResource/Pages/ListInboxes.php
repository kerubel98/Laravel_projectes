<?php

// app/Filament/Resources/InboxResource/Pages/ListInboxes.php

namespace App\Filament\Resources\InboxResource\Pages;

use App\Filament\Resources\InboxResource;
use App\Services\ZohoMailService;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Pagination\LengthAwarePaginator;

class ListInboxes extends ListRecords
{
    protected static string $resource = InboxResource::class;

    // Set default pagination
    protected int | string | array $perPage = 15;

    public function getTableRecords(): LengthAwarePaginator
    {
        $currentPage = $this->getTablePage(); // Correct method
        $perPage = $this->getTableRecordsPerPage(); // Correct method

        return cache()->remember('zoho_emails_'.auth()->id(), 300, function() use ($currentPage, $perPage) {
            $service = new ZohoMailService();
            $emails = $service->getInboxEmails($currentPage, $perPage);

            return new LengthAwarePaginator(
                $emails['messages'],
                $emails['total'],
                $perPage,
                $currentPage,
                [
                    'path' => route('filament.admin.resources.inboxes.index')
                ]
            );
        });
    }

    // Add pagination controls to view
    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Refresh Inbox')
                ->action(fn () => cache()->forget('zoho_emails_'.auth()->id()))
                ->icon('heroicon-o-arrow-path')
                ->color('primary'),

            Action::make('connect')
                ->label('Connect Zoho')
                ->url(route('zoho.connect'))
                ->icon('heroicon-o-link')
                ->visible(fn () => !auth()->user()->zoho_access_token)
        ];
    }
}



