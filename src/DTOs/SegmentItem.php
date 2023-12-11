<?php

namespace SegmentTrap\DTOs;

use Illuminate\Support\Arr;

class SegmentItem
{
    public function __construct(public string $method, public array $message)
    {
    }

    public function withDefaults(array $data): static
    {
        $this->message = array_replace_recursive($data, $this->message);

        return $this;
    }

    public function withOverrides(array $data): static
    {
        $this->message = array_replace_recursive($this->message, $data);

        return $this;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->message, $key, $default);
    }

    public function set(string $key, mixed $value): static
    {
        Arr::set($this->message, $key, $value);

        return $this;
    }

    public function has(string $key): bool
    {
        return Arr::has($this->message, $key);
    }
}
