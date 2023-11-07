<?php

namespace SegmentTrap\Modifiers;

use Closure;
use SegmentTrap\DTOs\SegmentItem;
use SegmentTrap\Identity\SegmentUser;

class SegmentIdentifyEvents
{
    public function handle(SegmentItem $item, Closure $next)
    {
        $item->message = array_replace(SegmentUser::session()->common(), $item->message);

        return $next($item);
    }
}
