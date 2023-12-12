<?php

namespace Hatchet\Segment\Drivers;

use Hatchet\Segment\Jobs\SyncWithSegment;

class QueueDriver extends AbstractBatchDriver
{
    public function flush(): bool
    {
        return static::flushMessages(
            fn (array $messages) => SyncWithSegment::dispatch($messages),
        );
    }
}
