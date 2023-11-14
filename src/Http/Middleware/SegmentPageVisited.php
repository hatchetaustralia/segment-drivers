<?php

namespace SegmentTrap\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use SegmentTrap\Facades\Segment;

class SegmentPageVisited
{
    use Helper\RouteConditional;

    public const DEFAULT_CATEGORY = 'default';

    /**
     * @param  array<mixed>  $properties
     */
    public function __construct(public string $category = self::DEFAULT_CATEGORY, public array $properties = [])
    {
    }

    public function handle(Request $request, Closure $next): mixed
    {
        if (! $this->allowed($request)) {
            return $next($request);
        }

        Segment::driver()->page([
            'name' => $request->getUri(),
            'category' => $this->category,
            'properties' => $this->properties,
        ]);

        return $next($request);
    }
}
