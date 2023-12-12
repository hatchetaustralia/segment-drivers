<?php

namespace Hatchet\Segment\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Queue\InteractsWithQueue;
use Segment\Segment;

/**
 * @method static PendingDispatch dispatch(array $messages)
 * @method static mixed dispatchSync(array $messages)
 * @method static PendingDispatch dispatchAfterResponse(array $messages)
 */
class SyncWithSegment implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    /**
     * @param  array<int, array<mixed>>  $messages
     */
    public function __construct(public readonly array $messages)
    {
    }

    public function handle(): void
    {
        Segment::init(config('segment.key'));

        foreach ($this->messages as $message) {
            /** @var string $method */
            /** @var array<string, mixed> $data */
            [$method, $data] = $message;

            Segment::{$method}($data);
        }

        Segment::flush();
    }
}
