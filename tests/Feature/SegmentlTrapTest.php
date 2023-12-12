<?php

declare(strict_types=1);

use Hatchet\Segment\Drivers\NullDriver;
use Hatchet\Segment\Drivers\StackDriver;
use Hatchet\Segment\Exceptions\InvalidArgumentException;
use Hatchet\Segment\SegmentAnalytics;
use Illuminate\Contracts\Config\Repository;

it('can instantiate SegmentAnalytics', function () {
    expect($this->app->get(SegmentAnalytics::class))->toBeInstanceOf(SegmentAnalytics::class);
});

it('can add multiple drivers to SegmentAnalytics', function () {
    $client = $this->app->get(SegmentAnalytics::class);
    $client->extend('null2', function () {
        return new NullDriver();
    });

    expect($client)->driver('null2')->toBeInstanceOf(NullDriver::class);
});

it('can retrieve a driver from SegmentAnalytics', function () {
    $trap = $this->app->get(SegmentAnalytics::class);
    $driver = $trap->driver('null');

    expect($driver)->toBeInstanceOf(NullDriver::class);
});

it('throws an exception when a driver cannot be found in SegmentAnalytics', function () {
    $this->app->get(SegmentAnalytics::class)->driver('abc');
})->throws(\InvalidArgumentException::class);

it('can set a default SegmentAnalytics driver', function () {
    config()->set('segment.default', 'null');

    $client = $this->app->get(SegmentAnalytics::class);

    expect($client->getDefaultDriver())->toBe('null')
        ->and($client->driver())->toBeInstanceOf(NullDriver::class);
});

it('throws an exception when a default driver has not been set in SegmentAnalytics', function () {
    $this->app->get(Repository::class)->set('segment.default', null);

    $this->app->get(SegmentAnalytics::class)->driver();
})->throws(InvalidArgumentException::class, 'A default SegmentAnalytics driver has not been configured');

it('can call `dispatch` on a SegmentAnalytics driver', function () {
    expect($this->app->get(SegmentAnalytics::class)->driver('null')->dispatch('abcdefg'))->toBeTrue();
});

it('can retrieve a driver with its own configuration', function () {
    config()->set('segment.drivers.stack.drivers', [
        'log',
        'null',
    ]);

    config()->set('segment.default', 'stack');

    $client = $this->app->get(SegmentAnalytics::class);
    $driver = $client->driver();
    expect($driver)
        ->toBeInstanceOf(StackDriver::class)
        ->config()->toBe([
            'drivers' => [
                'log',
                'null',
            ],
        ]);
});
