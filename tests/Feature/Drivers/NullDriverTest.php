<?php

use SegmentTrap\Drivers\NullDriver;
use SegmentTrap\Facades\SegmentTrap;

test('SegmentTrap null driver does nothing', function () {
    /** @var NullDriver $driver */
    $driver = SegmentTrap::driver('null');

    $success = $driver->dispatch('page', [
        'name' => 'Text Page Visit',
    ]);

    expect($success)->toBeTrue();
    expect($driver->flush())->toBeTrue();
});
