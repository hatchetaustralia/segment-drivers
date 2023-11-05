<?php

namespace SegmentTrap\Exceptions;

use SegmentTrap\Contracts\Driver;
use Throwable;

class ApiAuthenticationFailedException extends SegmentTrapDriverException
{
    public function __construct(
        Driver $driver,
        Throwable $previous = null
    ) {
        parent::__construct(
            driver: $driver,
            message: sprintf(
                'The API authentication failed for the %s driver',
                self::getDriverName($driver),
            ),
            code: 403,
            previous: $previous,
        );
    }

    public static function create(Driver $driver, Throwable $previous = null): self
    {
        return new self(
            driver: $driver,
            previous: $previous,
        );
    }
}
