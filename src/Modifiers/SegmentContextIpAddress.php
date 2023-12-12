<?php

namespace Hatchet\Segment\Modifiers;

use Closure;
use Hatchet\Segment\Contracts\Modifier;
use Hatchet\Segment\DTOs\SegmentItem;
use Illuminate\Http\Request;

class SegmentContextIpAddress implements Modifier
{
    public function __construct(public readonly Request $request)
    {
    }

    public function handle(SegmentItem $item, Closure $next): SegmentItem
    {
        $item->withDefaults([
            'context' => [
                'ip' => $this->request->ip(),
                'userAgent' => $this->request->userAgent(),
            ],
        ]);

        return $next($item);
    }
}
