<?php

declare(strict_types=1);

namespace SegmentTrap;

use Illuminate\Contracts\Container\Container;
use SegmentTrap\Contracts\Driver;
use SegmentTrap\Contracts\Factory;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SegmentTrapServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('segment')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(SegmentTrap::class);
        $this->app->alias(SegmentTrap::class, Factory::class);

        $this->app->bind(Driver::class, function (Container $app) {
            return $app->make(Factory::class)->driver(); // @phpstan-ignore-line
        });
    }
}
