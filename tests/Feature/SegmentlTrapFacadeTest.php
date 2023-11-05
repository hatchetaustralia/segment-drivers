<?php

declare(strict_types=1);

use SegmentTrap\Facades\SegmentTrap;

it('can call methods via the SegmentTrap facade', function () {
    expect(SegmentTrap::dispatch('Test'))->toBeTrue();
});
