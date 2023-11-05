<?php

use Illuminate\Support\Facades\Bus;
use SegmentTrap\Drivers\StackDriver;
use SegmentTrap\Facades\SegmentTrap;
use SegmentTrap\Jobs\SyncWithSegment;

test('SegmentTrap stack driver relays segment events to multiple drivers', function () {
    /**
     * Setup
     */
    Bus::fake();
    config()->set('segment.drivers.stack.drivers', [
        'queue',
        'sync',
        'after',
    ]);
    /** @var StackDriver $driver */
    $driver = SegmentTrap::driver('stack');

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
    Bus::assertNotDispatched(SyncWithSegment::class);
    Bus::assertNotDispatchedSync(SyncWithSegment::class);
    Bus::assertNotDispatchedAfterResponse(SyncWithSegment::class);

    /**
     * Assert that once flushed, the job is dispatched
     */
    expect($driver->flush())->toBeTrue();
    $assertion = fn (SyncWithSegment $job) => $job->messages === [
        [
            'page',
            ['name' => 'Text Page Visit'],
        ],
    ];
    Bus::assertDispatched($assertion);
    Bus::assertDispatchedSync($assertion);
    Bus::assertDispatchedAfterResponse($assertion);
});
