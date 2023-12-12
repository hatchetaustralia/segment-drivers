<?php

namespace Hatchet\Segment\Drivers;

use Illuminate\Database\Eloquent\Model;
use Hatchet\Segment\SegmentInvocation;

class EloquentDriver extends AbstractDriver
{
    public function getModelName(): string
    {
        /** @phpstan-ignore-next-line */
        return $this->config['model'];
    }

    public function newModel(): Model
    {
        $model = $this->getModelName();

        /** @var Model $model */
        $model = new $model();

        return $model;
    }

    public function dispatch(string $method, array $message = []): bool
    {
        $this->applyModifiers($method, $message);

        return $this->newModel()->fill([
            'method' => $method,
            'message' => $message,
            'invocation' => SegmentInvocation::id(),
        ])->save();
    }

    public function flush(): bool
    {
        return true;
    }
}
