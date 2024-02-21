<?php

declare(strict_types=1);

namespace Hatchet\Segment\Facades;

use Hatchet\Segment\Contracts\Driver;
use Hatchet\Segment\Drivers\FakeDriver;
use Hatchet\Segment\SegmentAnalytics;
use Hatchet\Segment\SegmentFake;
use Illuminate\Support\Facades\Facade;

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
 * @see SegmentAnalytics
 * @see SegmentFake
 */
class Segment extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SegmentAnalytics::class;
    }

    public static function getRealBase(): SegmentAnalytics
    {
        if (static::isFake()) {
            /** @var SegmentFake $fake */
            $fake = static::getFacadeRoot();

            return $fake->manager;
        }

        /** @var SegmentAnalytics $manager */
        $manager = static::getFacadeRoot();

        return $manager;
    }

    public static function fake(): SegmentFake
    {
        $manager = static::getRealBase();

        /** @var FakeDriver $driver */
        $driver = $manager->forgetDriver('fake')->driver('fake');

        $fake = new SegmentFake(self::$app, $manager, $driver);
        static::swap($fake);

        return $fake;
    }

    public static function unfake(): SegmentAnalytics
    {
        $real = static::getRealBase();
        static::swap($real);

        return $real;
    }
}
