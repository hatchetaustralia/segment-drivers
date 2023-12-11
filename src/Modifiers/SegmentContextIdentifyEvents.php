<?php

namespace SegmentTrap\Modifiers;

use Closure;
use SegmentTrap\Contracts\Modifier;
use SegmentTrap\DTOs\SegmentItem;
use SegmentTrap\DTOs\SegmentUser;

class SegmentContextIdentifyEvents implements Modifier
{
    public function __construct(public readonly SegmentUser $user)
    {
    }

    public function handle(SegmentItem $item, Closure $next): SegmentItem
    {
        if ($item->method === 'identify') {
            return $next($item);
        }

        $data = SegmentUser::identify()->common();
        $item->withDefaults($data);

        return $next($item);
    }
}
