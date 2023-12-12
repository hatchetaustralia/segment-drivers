<?php

namespace Hatchet\Segment\Contracts;

use Closure;
use Hatchet\Segment\DTOs\SegmentItem;

interface Modifier
{
    /**
     * @param  (Closure(SegmentItem $item): SegmentItem)  $next
     */
    public function handle(SegmentItem $item, Closure $next): SegmentItem;
}
