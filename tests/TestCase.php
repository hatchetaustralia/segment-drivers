<?php

declare(strict_types=1);

namespace SegmentTrap\Tests;

use SegmentTrap\Facades\Segment;
use SegmentTrap\SegmentInvocation;
use SegmentTrap\SegmentTrapServiceProvider;

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
