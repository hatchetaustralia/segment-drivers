<?php

use Hatchet\Segment\Drivers\FakeDriver;
use Hatchet\Segment\Drivers\LogDriver;
use Hatchet\Segment\Drivers\NullDriver;
use Hatchet\Segment\Facades\Segment;
use Hatchet\Segment\SegmentFake;

test('SegmentAnalytics fake driver records each segment event', function () {
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

test('SegmentAnalytics facade can swap the driver for a fake instance', function () {
    config()->set('segment.default', 'null');

    expect(Segment::driver())->toBeInstanceOf(NullDriver::class);

    expect(Segment::fake())->toBeInstanceOf(SegmentFake::class);

    expect(Segment::driver('null'))->toBeInstanceOf(FakeDriver::class);
    expect(Segment::driver('log'))->toBeInstanceOf(FakeDriver::class);

    Segment::unfake();

    expect(Segment::driver('null'))->toBeInstanceOf(NullDriver::class);
    expect(Segment::driver('log'))->toBeInstanceOf(LogDriver::class);
});
