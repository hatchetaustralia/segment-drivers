<?php

namespace Hatchet\Segment\Http\Middleware;

use Closure;
use Hatchet\Segment\Facades\Segment;
use Illuminate\Http\Request;

class SegmentIdentify
{
    use Helper\RouteConditional;

    public function handle(Request $request, Closure $next): mixed
    {
        if (! $this->allowed($request)) {
            return $next($request);
        }

        Segment::identify([]);

        return $next($request);
    }
}
