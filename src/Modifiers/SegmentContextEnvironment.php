<?php

namespace SegmentTrap\Modifiers;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use SegmentTrap\Contracts\Modifier;
use SegmentTrap\DTOs\SegmentItem;

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
