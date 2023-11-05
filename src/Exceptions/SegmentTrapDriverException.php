<?php

namespace SegmentTrap\Exceptions;

use Exception;
use SegmentTrap\Contracts\Driver;
use SegmentTrap\Contracts\SegmentTrapException;
use Throwable;

abstract class SegmentTrapDriverException extends Exception implements SegmentTrapException
{
    public function __construct(public readonly Driver $driver, string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            message: $message,
            code: $code,
            previous: $previous,
        );
    }

    public function driverName(): string
    {
        return self::getDriverName($this->driver);
    }

    protected static function getDriverName(Driver $driver): string
    {
        $class = get_class($driver);
        $class = str_contains($class, '\\') ? substr($class, strrpos($class, '\\') + 1) : $class;

        return str_replace(['SegmentTrap', 'Driver'], '', $class);
    }
}
