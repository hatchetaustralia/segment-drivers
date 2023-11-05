<?php

namespace SegmentTrap\Drivers;

use Closure;

abstract class AbstractBatchDriver extends AbstractDriver
{
    public static array $messages = [];

    public function dispatch(string $method, array $message = []): bool
    {
        static::$messages[static::class] ??= [];
        static::$messages[static::class][] = [
            $method,
            $message,
        ];

        return true;
    }

    public static function flushMessages(Closure $callback): bool
    {
        if (empty(static::$messages[static::class])) {
            return true;
        }

        $callback(static::$messages[static::class]);
        static::$messages[static::class] = [];

        return true;
    }
}
