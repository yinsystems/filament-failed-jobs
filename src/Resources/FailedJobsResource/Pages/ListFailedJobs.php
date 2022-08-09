<?php

namespace Amvisor\FilamentFailedJobs\Resources\FailedJobsResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Amvisor\FilamentFailedJobs\Resources\FailedJobsResource;

class ListFailedJobs extends ListRecords
{
	protected static string $resource = FailedJobsResource::class;
}
