<?php

use Illuminate\Support\Facades\Bus;
use SegmentTrap\Drivers\QueueDriver;
use SegmentTrap\Facades\SegmentTrap;
use SegmentTrap\Jobs\SyncWithSegment;

test('SegmentTrap queue driver runs a single job async', function () {
    /**
     * Setup
     */
    Bus::fake();
    /** @var QueueDriver $driver */
    $driver = SegmentTrap::driver('queue');

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

    /**
     * Assert that once flushed, the job is dispatched
     */
    expect($driver->flush())->toBeTrue();
    Bus::assertDispatched(fn (SyncWithSegment $job) => $job->messages === [
        [
            'page',
            ['name' => 'Text Page Visit'],
        ],
    ]);
});
