<?php

use SegmentTrap\Drivers\LogDriver;
use SegmentTrap\Facades\Segment;

test('SegmentTrap log driver writes a single event to the logger interface', function () {
    /** @var LogDriver $driver */
    $driver = Segment::driver('log');

    $success = $driver->dispatch('page', [
        'name' => 'Text Page Visit',
    ]);

    expect($success)->toBeTrue();
    expect($driver->flush())->toBeTrue();

    expect($driver->channel())->toBe('default');

    /**
     * TODO test the log output
     */
});
