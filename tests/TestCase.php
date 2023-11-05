<?php

declare(strict_types=1);

namespace SegmentTrap\Tests;

use SegmentTrap\Facades\SegmentTrap;
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
            'SegmentTrap' => SegmentTrap::class,
        ];
    }

    protected function tearDown(): void
    {
        SegmentTrap::driver('after')->flush();
        SegmentTrap::driver('log')->flush();
        SegmentTrap::driver('null')->flush();
        SegmentTrap::driver('queue')->flush();
        SegmentTrap::driver('stack')->flush();
        SegmentTrap::driver('sync')->flush();

        parent::tearDown();
    }
}
