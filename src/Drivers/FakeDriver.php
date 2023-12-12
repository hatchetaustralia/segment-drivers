<?php

namespace Hatchet\Segment\Drivers;

use Illuminate\Support\Testing\Fakes\Fake;

class FakeDriver extends AbstractBatchDriver implements Fake
{
    public function history(): array
    {
        return static::$messages[static::class] ?? [];
    }

    public function flush(): bool
    {
        self::flushMessages(fn () => null);

        return true;
    }

    public function __destruct()
    {
    }

    // public function assertDispatched(Closure $callback)
}
