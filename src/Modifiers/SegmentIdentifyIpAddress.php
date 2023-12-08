<?php

namespace SegmentTrap\Modifiers;

use Closure;
use Illuminate\Http\Request;
use SegmentTrap\DTOs\SegmentItem;

class SegmentIdentifyIpAddress
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