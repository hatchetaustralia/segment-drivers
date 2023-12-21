<?php

namespace Hatchet\Segment\Modifiers;

use Closure;
use Hatchet\Segment\Contracts\Modifier;
use Hatchet\Segment\DTOs\SegmentItem;
use Hatchet\Segment\DTOs\SegmentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SegmentIdentifyDefaults implements Modifier
{
    /**
     * @var array<int, (Closure(SegmentUser $user, SegmentItem $item, Request $request): void)> $callbacks
     */
    protected static array $callbacks = [];

    public function __construct(
        public readonly SegmentUser $user,
        public readonly Request $request,
    ) {
    }

    public function handle(SegmentItem $item, Closure $next): SegmentItem
    {
        if ($item->method !== 'identify') {
            return $next($item);
        }

        foreach (self::$callbacks as $callback) {
            $callback($this->user, $item, $this->request);
        }

        $item->withDefaults([
            'anonymousId' => session()->remember('segment_anonymous_id', fn () => (string) Str::uuid()),
        ]);
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
