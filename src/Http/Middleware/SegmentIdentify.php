<?php

namespace Hatchet\Segment\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hatchet\Segment\DTOs\SegmentUser;
use Hatchet\Segment\Facades\Segment;

class SegmentIdentify
{
    use Helper\RouteConditional;

    public function handle(Request $request, Closure $next,): mixed
    {
        if (! $this->allowed($request)) {
            return $next($request);
        }

        Segment::identify([]);

        return $next($request);
    }
}
