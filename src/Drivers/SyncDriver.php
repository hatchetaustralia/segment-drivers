<?php

namespace SegmentTrap\Drivers;

use SegmentTrap\Jobs\SyncWithSegment;

class SyncDriver extends AbstractBatchDriver
{
    public function flush(): bool
    {
        return static::flushMessages(
            fn (array $messages) => SyncWithSegment::dispatchSync($messages),
        );
    }
}
