<?php

declare(strict_types=1);

namespace SegmentTrap\Exceptions;

use SegmentTrap\Contracts\SegmentTrapException;

class InvalidArgumentException extends \InvalidArgumentException implements SegmentTrapException
{
}
