<?php

use Hatchet\Segment\Drivers\AfterDriver;
use Hatchet\Segment\Facades\Segment;
use Hatchet\Segment\Jobs\SyncWithSegment;
use Illuminate\Support\Facades\Bus;

test('SegmentTrap after driver runs a single job after the response', function () {
    /**
     * Setup
     */
    Bus::fake();
    /** @var AfterDriver $driver */
    $driver = Segment::driver('after');

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
    Bus::assertNotDispatchedAfterResponse(SyncWithSegment::class);

    /**
     * Assert that once flushed, the job is dispatched
     */
    expect($driver->flush())->toBeTrue();
    Bus::assertDispatchedAfterResponse(fn (SyncWithSegment $job) => $job->messages === [
        [
            'page',
            ['name' => 'Text Page Visit'],
        ],
    ]);
});
