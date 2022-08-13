<?php

namespace Amvisor\FilamentFailedJobs;

use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Amvisor\FilamentFailedJobs\Resources\FailedJobsResource;

class FilamentFailedJobsServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-failed-jobs';

    protected array $resources = [
        FailedJobsResource::class,
    ];

    public function configurePackage(Package $package): void
    {
        $package->name('filament-failed-jobs');
    }

}
