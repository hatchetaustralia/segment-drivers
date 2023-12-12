<?php

namespace Hatchet\Segment\Modifiers;

use Closure;
use Hatchet\Segment\Contracts\Modifier;
use Hatchet\Segment\DTOs\SegmentItem;

class SegmentTimestamps implements Modifier
{
    public function handle(SegmentItem $item, Closure $next): SegmentItem
    {
        $timestamp = now('UTC')->format('Y-m-d\TH:i:s.v\Z');

        $item->withDefaults([
            'originalTimestamp' => $timestamp,
            'receivedAt' => $timestamp,
            'sentAt' => $timestamp,
        ])->withOverrides([
            /**
             * Let the Segment PHP SDK do its thing
             */
            'timestamp' => null,
        ]);

        return $next($item);
    }
}
