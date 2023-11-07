<?php

namespace SegmentTrap\Http\Middleware\Helper;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @property array<string> $excludePath
 * @property null|array<string> $includePath
 * @property null|array<string> $excludeMethod
 * @property null|array<string> $includeMethod
 */
trait RouteConditional
{
    protected array $excludePath = [
        '/segment/*',
        '/nova-api/*',
        '/assets/*',
        '/ajax/*',
    ];

    public function allowed(Request $request): bool
    {
        $uri = '/'.ltrim($request->path(), '/');

        if (property_exists($this, 'excludePath')) {
            $excludePath = $this->excludePath;

            if (Str::is($excludePath, $uri, )) {
                return false;
            }
        }

        if (property_exists($this, 'includePath')) {
            $includePath = $this->includePath;

            if (! Str::is($includePath, $uri)) {
                return false;
            }
        }

        if (property_exists($this, 'excludeMethod')) {
            $excludeMethod = $this->excludeMethod;

            if (in_array($request->method(), $excludeMethod)) {
                return false;
            }
        }

        if (property_exists($this, 'includeMethod')) {
            $includeMethod = $this->includeMethod;

            if (! in_array($request->method(), $includeMethod)) {
                return false;
            }
        }

        return true;
    }
}
