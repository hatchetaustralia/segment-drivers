<?php

declare(strict_types=1);

namespace SegmentTrap\Facades;

use Illuminate\Support\Facades\Facade;
use SegmentTrap\Contracts\Driver;
use SegmentTrap\Drivers\FakeDriver;
use SegmentTrap\SegmentFake;
use SegmentTrap\SegmentTrap;

/**
 * @method static Driver driver(string|null $driver = null)
 * @method static bool dispatch(string $method, array $message = [])
 * @method SegmentItem pipeThroughMiddleware(SegmentItem $item)
 *
 * @see SegmentTrap
 * @see SegmentFake
 */
class Segment extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SegmentTrap::class;
    }

    public static function getRealBase(): SegmentTrap
    {
        if (static::isFake()) {
            /** @var SegmentFake $fake */
            $fake = static::getFacadeRoot();

            return $fake->manager;
        }

        /** @var SegmentTrap $manager */
        $manager = static::getFacadeRoot();

        return $manager;
    }

    public static function fake(): SegmentFake
    {
        $manager = static::getRealBase();

        /** @var FakeDriver $driver */
        $driver = $manager->forgetDriver('fake')->driver('fake');

        $fake = new SegmentFake($manager, $driver);
        static::swap($fake);

        return $fake;
    }

    public static function unfake(): SegmentTrap
    {
        $real = static::getRealBase();
        static::swap($real);

        return $real;
    }
}
