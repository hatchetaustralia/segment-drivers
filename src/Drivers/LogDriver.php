<?php

namespace SegmentTrap\Drivers;

use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

class LogDriver extends AbstractDriver
{
    protected ?LoggerInterface $logger = null;

    public function logger(): LoggerInterface
    {
        return $this->logger ??= Log::channel($this->channel());
    }

    public function channel(): string
    {
        /** @phpstan-ignore-next-line */
        return $this->config['channel'];
    }

    public function dispatch(string $method, array $message = []): bool
    {
        $this->logger()->debug('Segment::'.$method, $message);

        return true;
    }

    public function flush(): bool
    {
        return true;
    }

    public function __destruct()
    {
    }
}
