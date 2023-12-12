<?php

namespace Hatchet\Segment\Drivers;

class NullDriver extends AbstractDriver
{
    public function dispatch(string $method, array $message = []): bool
    {
        // Still run through middleware for authentic testing
        $this->applyModifiers($method, $message);

        return true;
    }

    public function flush(): bool
    {
        return true;
    }
}
