<?php

use Illuminate\Support\Facades\Bus;
use SegmentTrap\Drivers\SyncDriver;
use SegmentTrap\Facades\SegmentTrap;
use SegmentTrap\SegmentInvocation;
use SegmentTrap\Tests\Fixtures\CustomSegmentModel;

test('SegmentTrap eloquent driver inserts records for each event', function () {
    /**
     * Setup
     */
    CustomSegmentModel::$saveHistory = [];
    Bus::fake();
    config()->set('segment.drivers.eloquent.model', CustomSegmentModel::class);
    /** @var SyncDriver $driver */
    $driver = SegmentTrap::driver('eloquent');

    /**
     * Trigger a segment event
     */
    $success = $driver->dispatch('page', [
        'name' => 'Text Page Visit',
    ]);

    /**
     * Assert that the models are inserted immediate
     */
    expect(CustomSegmentModel::$saveHistory)->toBe([
        [
            'method' => 'page',
            'message' => ['name' => 'Text Page Visit'],
            'invocation' => SegmentInvocation::id(),
        ],
    ]);

    /**
     * Assert that the flush method doesn't do anything extra
     */
    expect($driver->flush())->toBeTrue();
    expect(CustomSegmentModel::$saveHistory)->toBe([
        [
            'method' => 'page',
            'message' => ['name' => 'Text Page Visit'],
            'invocation' => SegmentInvocation::id(),
        ],
    ]);
});
