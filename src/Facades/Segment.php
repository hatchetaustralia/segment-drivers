<?php

declare(strict_types=1);

namespace Hatchet\Segment\Facades;

use Illuminate\Support\Facades\Facade;
use Hatchet\Segment\Contracts\Driver;
use Hatchet\Segment\Drivers\FakeDriver;
use Hatchet\Segment\SegmentFake;
use Hatchet\Segment\SegmentTrap;

/**
 * @method static Driver driver(string|null $driver = null)
 * @method static bool dispatch(string $method, array $message = [])
 * @method SegmentItem pipeThroughMiddleware(SegmentItem $item)
 * @method static bool track(array $message)
 * @method static bool identify(array $message)
 * @method static bool group(array $message)
 * @method static bool page(array $message)
 * @method static bool screen(array $message)
 * @method static bool alias(array $message)
 * @method static bool flush()
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
