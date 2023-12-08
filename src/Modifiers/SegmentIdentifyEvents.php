<?php

namespace SegmentTrap\Modifiers;

use Closure;
use SegmentTrap\DTOs\SegmentItem;
use SegmentTrap\Identity\SegmentUser;

class SegmentIdentifyEvents
{
    public function handle(SegmentItem $item, Closure $next): SegmentItem
    {
        $item->withOverrides(SegmentUser::session()->common());

        return $next($item);
    }
}
