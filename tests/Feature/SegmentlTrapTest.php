<?php

declare(strict_types=1);

use Hatchet\Segment\Drivers\NullDriver;
use Hatchet\Segment\Drivers\StackDriver;
use Hatchet\Segment\Exceptions\InvalidArgumentException;
use Hatchet\Segment\SegmentTrap;
use Illuminate\Contracts\Config\Repository;

it('can instantiate SegmentTrap', function () {
    expect($this->app->get(SegmentTrap::class))->toBeInstanceOf(SegmentTrap::class);
});

it('can add multiple drivers to SegmentTrap', function () {
    $client = $this->app->get(SegmentTrap::class);
    $client->extend('null2', function () {
        return new NullDriver();
    });

    expect($client)->driver('null2')->toBeInstanceOf(NullDriver::class);
});

it('can retrieve a driver from SegmentTrap', function () {
    $trap = $this->app->get(SegmentTrap::class);
    $driver = $trap->driver('null');

    expect($driver)->toBeInstanceOf(NullDriver::class);
});

it('throws an exception when a driver cannot be found in SegmentTrap', function () {
    $this->app->get(SegmentTrap::class)->driver('abc');
})->throws(\InvalidArgumentException::class);

it('can set a default SegmentTrap driver', function () {
    config()->set('segment.default', 'null');

    $client = $this->app->get(SegmentTrap::class);

    expect($client->getDefaultDriver())->toBe('null')
        ->and($client->driver())->toBeInstanceOf(NullDriver::class);
});

it('throws an exception when a default driver has not been set in SegmentTrap', function () {
    $this->app->get(Repository::class)->set('segment.default', null);

    $this->app->get(SegmentTrap::class)->driver();
})->throws(InvalidArgumentException::class, 'A default SegmentTrap driver has not been configured');

it('can call `dispatch` on a SegmentTrap driver', function () {
    expect($this->app->get(SegmentTrap::class)->driver('null')->dispatch('abcdefg'))->toBeTrue();
});

it('can retrieve a driver with its own configuration', function () {
    config()->set('segment.drivers.stack.drivers', [
        'log',
        'null',
    ]);

    config()->set('segment.default', 'stack');

    $client = $this->app->get(SegmentTrap::class);
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
