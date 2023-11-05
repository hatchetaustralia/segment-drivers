<?php

namespace SegmentTrap\Drivers;

class NullDriver extends AbstractDriver
{
    public function dispatch(string $method, array $message = []): bool
    {
        return true;
    }

    public function flush(): bool
    {
        return true;
    }
}
