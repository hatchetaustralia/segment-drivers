<?php

declare(strict_types=1);

namespace SegmentTrap;

use Illuminate\Support\Testing\Fakes\Fake;
use Illuminate\Support\Traits\ForwardsCalls;
use SegmentTrap\Drivers\FakeDriver;

/**
 * @mixin FakeDriver
 */
class SegmentFake extends SegmentTrap implements Fake
{
    use ForwardsCalls;

    public function __construct(public SegmentTrap $manager, public FakeDriver $driver)
    {
    }

    public function __call($name, $arguments)
    {
        return $this->forwardDecoratedCallTo($this->driver, $name, $arguments);
    }

    /**
     * All drivers now point to the fake driver
     *
     * @param  string|null  $driver
     */
    public function driver($driver = null): FakeDriver
    {
        return $this->driver;
    }
}
