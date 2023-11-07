<?php

use SegmentTrap\Drivers\FakeDriver;
use SegmentTrap\Drivers\LogDriver;
use SegmentTrap\Drivers\NullDriver;
use SegmentTrap\Facades\Segment;
use SegmentTrap\SegmentFake;

test('SegmentTrap fake driver records each segment event', function () {
    /**
     * Setup
     */
    /** @var FakeDriver $driver */
    $driver = Segment::driver('fake');

    /**
     * Trigger a segment event
     */
    $success = $driver->dispatch('page', [
        'name' => 'Text Page Visit',
    ]);

    /**
     * Assert that nothing really happened
     */
    expect($success)->toBeTrue();
    expect($driver->history())->toBe([
        [
            'page',
            ['name' => 'Text Page Visit'],
        ],
    ]);

    /**
     * Assert that once flushed, the job is dispatched
     */
    expect($driver->flush())->toBeTrue();
    expect($driver->history())->toBe([]);
});

test('SegmentTrap facade can swap the driver for a fake instance', function () {
    config()->set('segment.default', 'null');

    expect(Segment::driver())->toBeInstanceOf(NullDriver::class);

    expect(Segment::fake())->toBeInstanceOf(SegmentFake::class);

    expect(Segment::driver('null'))->toBeInstanceOf(FakeDriver::class);
    expect(Segment::driver('log'))->toBeInstanceOf(FakeDriver::class);

    Segment::unfake();

    expect(Segment::driver('null'))->toBeInstanceOf(NullDriver::class);
    expect(Segment::driver('log'))->toBeInstanceOf(LogDriver::class);
});
