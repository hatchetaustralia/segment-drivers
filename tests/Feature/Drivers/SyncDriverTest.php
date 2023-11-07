<?php

use Illuminate\Support\Facades\Bus;
use SegmentTrap\Drivers\SyncDriver;
use SegmentTrap\Facades\Segment;
use SegmentTrap\Jobs\SyncWithSegment;

test('SegmentTrap sync driver runs a single job sync', function () {
    /**
     * Setup
     */
    Bus::fake();
    /** @var SyncDriver $driver */
    $driver = Segment::driver('sync');

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
    Bus::assertNotDispatchedSync(SyncWithSegment::class);

    /**
     * Assert that once flushed, the job is dispatched
     */
    expect($driver->flush())->toBeTrue();
    Bus::assertDispatchedSync(fn (SyncWithSegment $job) => $job->messages === [
        [
            'page',
            ['name' => 'Text Page Visit'],
        ],
    ]);
});
