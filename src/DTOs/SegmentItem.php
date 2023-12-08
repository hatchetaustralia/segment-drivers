<?php

namespace SegmentTrap\DTOs;

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
}
