<?php

namespace Hatchet\Segment;

use Illuminate\Support\Str;

class SegmentInvocation
{
    public static ?string $id = null;

    public static function id(): string
    {
        return self::$id ??= (string) Str::uuid();
    }

    public static function reset(): void
    {
        self::$id = null;
    }
}
