<?php

declare(strict_types=1);

namespace Hatchet\Segment\Exceptions;

use Hatchet\Segment\Contracts\SegmentAnalyticsException;

class InvalidArgumentException extends \InvalidArgumentException implements SegmentAnalyticsException
{
}
