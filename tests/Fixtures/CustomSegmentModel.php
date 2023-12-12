<?php

namespace Hatchet\Segment\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;

class CustomSegmentModel extends Model
{
    public static array $saveHistory = [];

    public $guarded = [];

    public function save(array $options = []): bool
    {
        self::$saveHistory[] = $this->only([
            'method',
            'message',
            'invocation',
        ]);

        return true;
    }
}
