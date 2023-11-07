<?php

declare(strict_types=1);

use SegmentTrap\Facades\Segment;

it('can call methods via the SegmentTrap facade', function () {
    expect(Segment::dispatch('Test'))->toBeTrue();
});
