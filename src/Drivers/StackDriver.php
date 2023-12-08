<?php

namespace SegmentTrap\Drivers;

use SegmentTrap\Contracts\Driver;
use SegmentTrap\Facades\Segment;

class StackDriver extends AbstractDriver
{
    protected ?array $drivers = null;

    /**
     * @return array<int, Driver>
     */
    public function drivers(): array
    {
        /** @var array<int, string> $drivers */
        $drivers = $this->config['drivers'] ?? [];

        return $this->drivers ??= array_map(
            fn (string $driver) => Segment::driver($driver),
            $drivers,
        );
    }

    /**
     * Tracks a user action
     *
     * @param  array<mixed>  $message
     */
    public function dispatch(string $method, array $message = []): bool
    {
        $this->applyModifiers($method, $message);
        $overall = true;

        foreach ($this->drivers() as $driver) {
            $overall = $driver->dispatch($method, $message) && $overall;
        }

        return $overall;
    }

    public function flush(): bool
    {
        $overall = true;

        foreach ($this->drivers() as $driver) {
            $overall = $driver->flush() && $overall;
        }

        return $overall;
    }
}
