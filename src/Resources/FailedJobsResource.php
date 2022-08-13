<?php

namespace Amvisor\FilamentFailedJobs\Resources;

use Amvisor\FilamentFailedJobs\Models\FailedJob;
use Amvisor\FilamentFailedJobs\Resources\FailedJobsResource\Pages\ListFailedJobs;
use Illuminate\Support\Collection;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
        return (string)FailedJob::query()->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('failed_at')->disabled(),
                TextInput::make('id')->disabled(),
                TextInput::make('uuid')->disabled(),
                TextInput::make('connection')->disabled(),
                TextInput::make('queue')->disabled(),
                TextArea::make('payload')->disabled(),
                TextArea::make('exception')->disabled(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('failed_at')->sortable()->searchable(),
                TextColumn::make('id')->sortable()->searchable(),
                TextColumn::make('uuid')->sortable()->searchable(),
                TextColumn::make('connection')->sortable()->searchable(),
                TextColumn::make('queue')->sortable()->searchable(),
            ])
            ->filters([])
            ->bulkActions([
                BulkAction::make('retry')
                    ->label('Retry')
                    ->requiresConfirmation()
                    ->action(function (Collection $records): void {
                        foreach ($records as $record) {
                            Artisan::call("queue:retry {$record->uuid}");
                        }
                        Notification::make()
                            ->body("{$records->count()} jobs have been pushed back onto the queue.")
                            ->title("Failed Jobs")
                            ->success()
                            ->send();
                    }),
                BulkAction::make('forget')
                    ->label('Forget')
                    ->requiresConfirmation()
                    ->action(function (Collection $records): void {
                        foreach ($records as $record) {
                            Artisan::call("queue:forget {$record->uuid}");
                        }
                        Notification::make()
                            ->body("{$records->count()} jobs have been forgotten.")
                            ->title("Failed Jobs Forgotten")
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                ViewAction::make('View'),
                Action::make('retry')
                    ->label('Retry')
                    ->requiresConfirmation()
                    ->action(function (FailedJob $record): void {
                        Artisan::call("queue:retry {$record->uuid}");
                        Notification::make()
                            ->title("The job with uuid '{$record->uuid}' has been pushed back onto the queue.")
                            ->body("Failed Jobs")
                            ->success()
                            ->send();
                    }),
            ]);

    }

    public static function getPages(): array
    {
        return [
            'index' => ListFailedJobs::route('/'),
        ];
    }
}
