<?php

namespace Hatchet\Segment\Drivers;

use Segment\Segment;

class SyncDriver extends AbstractDriver
{
    public function dispatch(string $method, array $message = []): bool
    {
        $this->applyModifiers($method, $message);

        Segment::{$method}($message);

        return true;
    }

    public function flush(): bool
    {
        return true;
    }
}
