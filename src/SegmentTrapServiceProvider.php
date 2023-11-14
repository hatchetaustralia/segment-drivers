<?php

declare(strict_types=1);

namespace SegmentTrap;

use Illuminate\Auth\Events;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use SegmentTrap\Contracts\Driver;
use SegmentTrap\Contracts\Factory;
use SegmentTrap\Facades\Segment;
use SegmentTrap\Identity\SegmentUser;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * @property-read Application $app
 */
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
            return $app->make(Factory::class)->driver(); /** @phpstan-ignore-line */
        });

        $middleware = $this->app['config']->get('segment.relay.middleware', []); /** @phpstan-ignore-line */
        Route::middleware($middleware)->group(fn () => $this->loadRoutesFrom(dirname(__DIR__).'/routes/segment-routes.php'));

        $this->app->terminating(fn () => SegmentTrap::shutdown());

        $this->listenToEvents();
    }

    protected function listenToEvents(): void
    {
        Event::listen(Events\Authenticated::class, fn () => SegmentUser::session()->set(Auth::user()));
        Event::listen(Events\Failed::class, fn () => Segment::driver()->track([
            'event' => 'Failed Login',
        ]));
        Event::listen(Events\Login::class, fn () => SegmentUser::session()->set(Auth::user()));
        Event::listen(Events\Logout::class, fn () => SegmentUser::session()->set(null));
        Event::listen(Events\PasswordReset::class, fn () => Segment::driver()->track([
            'event' => 'Password Reset',
        ]));
        Event::listen(Events\Registered::class, fn () => Segment::driver()->track([
            'event' => 'Registered',
        ]));
        Event::listen(Events\Verified::class, fn () => Segment::driver()->track([
            'event' => 'Verified',
        ]));
    }
}
