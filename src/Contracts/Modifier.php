<?php

namespace SegmentTrap\Contracts;

use Closure;
use SegmentTrap\DTOs\SegmentItem;

interface Modifier
{
    /**
     * @param (Closure(SegmentItem $item): SegmentItem) $next
     */
    public function handle(SegmentItem $item, Closure $next): SegmentItem;
}
