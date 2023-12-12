<?php

declare(strict_types=1);

namespace Hatchet\Segment\Exceptions;

use Hatchet\Segment\Contracts\SegmentTrapException;

class InvalidArgumentException extends \InvalidArgumentException implements SegmentTrapException
{
}
