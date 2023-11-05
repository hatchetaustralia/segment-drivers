<?php

declare(strict_types=1);

namespace SegmentTrap\Facades;

use Illuminate\Support\Facades\Facade;
use SegmentTrap\Contracts\Driver;

/**
 * @method static Driver driver(string|null $driver = null)
 * @method static bool dispatch(string $method, array $message = [])
 */
class SegmentTrap extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \SegmentTrap\SegmentTrap::class;
    }
}
