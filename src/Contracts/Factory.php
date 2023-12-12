<?php

declare(strict_types=1);

namespace Hatchet\Segment\Contracts;

interface Factory
{
    /**
     * Get a SegmentTrap driver implementation.
     *
     * @param  string  $driver
     * @return Driver
     */
    public function driver($driver = null);
}
