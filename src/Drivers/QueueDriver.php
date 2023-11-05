<?php

namespace SegmentTrap\Drivers;

use SegmentTrap\Jobs\SyncWithSegment;

class QueueDriver extends AbstractBatchDriver
{
    public function flush(): bool
    {
        return static::flushMessages(
            fn (array $messages) => SyncWithSegment::dispatch($messages),
        );
    }
}
