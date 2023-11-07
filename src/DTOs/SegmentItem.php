<?php

namespace SegmentTrap\DTOs;

class SegmentItem
{
    public function __construct(public string $method, public array $message)
    {
    }
}
