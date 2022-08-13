<?php

namespace Amvisor\FilamentFailedJobs\Resources\FailedJobsResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Amvisor\FilamentFailedJobs\Resources\FailedJobsResource;
use Illuminate\Support\Facades\Artisan;

class ListFailedJobs extends ListRecords
{
    protected static string $resource = FailedJobsResource::class;

    public function getActions(): array
    {
        return [
            Action::make('retry_all')
                ->label('Retry all failed Jobs')
                ->requiresConfirmation()
                ->action(function (): void {
                    Artisan::call('queue:retry all');
                    Notification::make()
                        ->body('All failed jobs have been pushed back onto the queue.')
                        ->title('Failed Jobs.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
