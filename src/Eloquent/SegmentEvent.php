<?php

namespace Hatchet\Segment\Eloquent;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read string $method
 * @property-read array<string, mixed> $message
 * @property-read string $invocation
 */
class SegmentEvent extends Model
{
    public $table = 'segment_events';

    public $guarded = [];

    public $casts = [
        'method' => 'string',
        'message' => 'array',
        'invocation' => 'string',
    ];
}
