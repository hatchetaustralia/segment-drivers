<?php

namespace Hatchet\Segment\Drivers;

use Hatchet\Segment\Jobs\SyncWithSegment;

class AfterDriver extends AbstractBatchDriver
{
    public function flush(): bool
    {
        return static::flushMessages(
            fn (array $messages) => SyncWithSegment::dispatchAfterResponse($messages),
        );
    }
}
