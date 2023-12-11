<?php

namespace SegmentTrap\Modifiers;

use Closure;
use Illuminate\Http\Request;
use SegmentTrap\Contracts\Modifier;
use SegmentTrap\DTOs\SegmentItem;
use SegmentTrap\DTOs\SegmentUser;

class SegmentIdentifyDefaults implements Modifier
{
    /**
     * @var array<int, (Closure(SegmentUser $identify, SegmentItem $item, Request $request): void)> $callbacks
     */
    protected static array $callbacks = [];

    public function __construct(
        public readonly SegmentUser $user,
        public readonly Request $request,
    )
    {
    }

    public function handle(SegmentItem $item, Closure $next): SegmentItem
    {
        if ($item->method !== 'identify') {
            return $next($item);
        }

        foreach (self::$callbacks as $callback) {
            $callback($this->user, $item, $this->request);
        }

        $item->withDefaults($this->user->toArray());

        return $next($item);
    }

    /**
     * @param  (Closure(SegmentUser $identify, SegmentItem $item, Request $request): void)  $callback
     */
    public static function identify(Closure $callback): void
    {
        self::$callbacks[] = $callback;
    }
}
