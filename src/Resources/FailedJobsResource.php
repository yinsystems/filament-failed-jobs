<?php

namespace Amvisor\FilamentFailedJobs\Resources;

use Amvisor\FilamentFailedJobs\Models\FailedJob;
use Amvisor\FilamentFailedJobs\Resources\FailedJobsResource\Pages\ListFailedJobs;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Illuminate\Support\Facades\Artisan;

class FailedJobsResource extends Resource
{
    protected static ?string $model = FailedJob::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-circle';

	protected static function getNavigationBadge(): ?string
	{
		return (string) FailedJob::query()->count();
	}

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable(),
                TextColumn::make('uuid')->sortable()->searchable(),
                TextColumn::make('connection')->sortable()->searchable(),
                TextColumn::make('queue')->sortable()->searchable(),
                TextColumn::make('payload')->sortable()->searchable(),
                TextColumn::make('exception')->sortable()->searchable(),
                TextColumn::make('failed_at')->sortable()->searchable(),
            ])
            ->filters([])
            ->actions([
                Action::make('retry')
                    ->label('Retry')
                    ->requiresConfirmation()
                    ->action(function(FailedJob $record): void {
                        Artisan::call('queue:retry', [$record->uuid]);
                        Notification::make() 
                            ->title("The job with uuid '$record->uuid' has been pushed back onto the queue.")
                            ->success()
                            ->send();
                    })
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => ListFailedJobs::route('/'),
        ];
    }    
}
