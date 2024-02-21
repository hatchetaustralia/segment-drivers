<?php

namespace Hatchet\Segment\Modifiers;

use Closure;
use Hatchet\Segment\Contracts\Modifier;
use Hatchet\Segment\DTOs\SegmentItem;
use Hatchet\Segment\DTOs\SegmentUser;

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

        $defaults = [
            'anonymousId' => $item->get('anonymousId'),
        ];
        $data = SegmentUser::identify()->withDefaults($defaults)->common();
        $item->withDefaults($data);

        return $next($item);
    }
}
