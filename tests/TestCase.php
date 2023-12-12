<?php

declare(strict_types=1);

namespace Hatchet\Segment\Tests;

use Hatchet\Segment\Facades\Segment;
use Hatchet\Segment\SegmentInvocation;
use Hatchet\Segment\SegmentTrapServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [SegmentTrapServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'SegmentTrap' => Segment::class,
        ];
    }

    protected function setUp(): void
    {
        SegmentInvocation::reset();

        parent::setUp();
    }

    protected function tearDown(): void
    {
        Segment::driver('after')->flush();
        Segment::driver('log')->flush();
        Segment::driver('null')->flush();
        Segment::driver('queue')->flush();
        Segment::driver('stack')->flush();
        Segment::driver('sync')->flush();

        parent::tearDown();
    }
}
