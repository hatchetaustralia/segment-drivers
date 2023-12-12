<?php

declare(strict_types=1);

use Hatchet\Segment\Facades\Segment;

it('can call methods via the SegmentAnalytics facade', function () {
    expect(Segment::dispatch('Test'))->toBeTrue();
});
