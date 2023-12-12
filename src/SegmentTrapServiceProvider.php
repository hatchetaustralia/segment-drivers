<?php

declare(strict_types=1);

namespace Hatchet\Segment;

use Hatchet\Segment\Contracts\Driver;
use Hatchet\Segment\Contracts\Factory;
use Hatchet\Segment\DTOs\SegmentUser;
use Hatchet\Segment\Facades\Segment;
use Illuminate\Auth\Events;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
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

        $this->app->singleton(SegmentUser::class);

        $this->app->bind(Driver::class, function (Container $app) {
            return $app->make(Factory::class)->driver(); /** @phpstan-ignore-line */
        });

        $middleware = $this->app['config']->get('segment.relay.middleware', []); /** @phpstan-ignore-line */
        Route::middleware($middleware)->group(fn () => $this->loadRoutesFrom(dirname(__DIR__).'/routes/segment-routes.php'));

        $this->app->terminating(fn () => SegmentTrap::shutdown());

        if ($this->app['config']->get('segment.events.auth')) {
            self::fireAuthEvents();
        }
    }

    public static function fireAuthEvents(): void
    {
        Event::listen(Events\Failed::class, fn () => Segment::track([
            'category' => 'auth',
            'event' => 'Authenticated Login',
        ]));

        Event::listen(Events\Failed::class, fn () => Segment::track([
            'category' => 'auth',
            'event' => 'Failed Login',
        ]));

        Event::listen(Events\Login::class, fn () => Segment::track([
            'category' => 'auth',
            'event' => 'Login',
        ]));

        Event::listen(Events\Logout::class, fn () => Segment::track([
            'category' => 'auth',
            'event' => 'Logout',
        ]));

        Event::listen(Events\PasswordReset::class, fn () => Segment::track([
            'category' => 'auth',
            'event' => 'Password Reset',
        ]));

        Event::listen(Events\Registered::class, fn () => Segment::track([
            'category' => 'auth',
            'event' => 'Registered',
        ]));

        Event::listen(Events\Verified::class, fn () => Segment::track([
            'category' => 'auth',
            'event' => 'Verified',
        ]));
    }
}
