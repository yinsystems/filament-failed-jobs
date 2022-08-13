<?php

namespace Amvisor\FilamentFailedJobs\Resources;

use App\Models\FailedJobs;
use Filament\Resources\Resource;
use Filament\Resources\Table;

class FailedJobsResource extends Resource
{
	protected static ?string $model = FailedJobs::class;

	protected static ?string $navigationIcon = 'heroicon-o-exclamation-circle';

	public static function table(Table $table): Table
	{
		return $table
			->columns([
			])
			->filters([
			])
			->actions([
			])
			->bulkActions([
			]);
	}
}
