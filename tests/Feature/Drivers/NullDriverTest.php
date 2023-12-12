<?php

use Hatchet\Segment\Drivers\NullDriver;
use Hatchet\Segment\Facades\Segment;

test('SegmentAnalytics null driver does nothing', function () {
    /** @var NullDriver $driver */
    $driver = Segment::driver('null');

    $success = $driver->dispatch('page', [
        'name' => 'Text Page Visit',
    ]);

    expect($success)->toBeTrue();
    expect($driver->flush())->toBeTrue();
});
