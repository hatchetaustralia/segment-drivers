<?php

namespace SegmentTrap\Drivers;

use Segment\Segment;

class SyncDriver extends AbstractDriver
{
    public function dispatch(string $method, array $message = []): bool
    {
        Segment::{$method}($message);

        return true;
    }

    public function flush(): bool
    {
        return true;
    }
}
