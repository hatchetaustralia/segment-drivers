<?php

namespace Hatchet\Segment\Drivers;

use Hatchet\Segment\Jobs\SyncWithSegment;

class SyncDriver extends AbstractDriver
{
    public function dispatch(string $method, array $message = []): bool
    {
        $this->applyModifiers($method, $message);

        SyncWithSegment::dispatchSync([
            [$method, $message],
        ]);

        return true;
    }

    public function flush(): bool
    {
        return true;
    }
}
