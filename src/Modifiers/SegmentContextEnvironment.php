<?php

namespace Hatchet\Segment\Modifiers;

use Closure;
use Hatchet\Segment\Contracts\Modifier;
use Hatchet\Segment\DTOs\SegmentItem;
use Illuminate\Contracts\Foundation\Application;

class SegmentContextEnvironment implements Modifier
{
    public function __construct(public readonly Application $app)
    {
    }

    public function handle(SegmentItem $item, Closure $next): SegmentItem
    {
        $item->withDefaults([
            'context' => [
                'environment' => $this->app->environment(),
            ],
        ]);

        return $next($item);
    }
}
