<?php

declare(strict_types=1);

namespace SegmentTrap;

use Illuminate\Foundation\Application;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Manager;
use SegmentTrap\Contracts\Driver;
use SegmentTrap\Contracts\Factory;
use SegmentTrap\Drivers\AfterDriver;
use SegmentTrap\Drivers\EloquentDriver;
use SegmentTrap\Drivers\FakeDriver;
use SegmentTrap\Drivers\LogDriver;
use SegmentTrap\Drivers\NullDriver;
use SegmentTrap\Drivers\QueueDriver;
use SegmentTrap\Drivers\StackDriver;
use SegmentTrap\Drivers\SyncDriver;
use SegmentTrap\DTOs\SegmentItem;
use SegmentTrap\Exceptions\InvalidArgumentException;

/**
 * @property Application $container
 */
class SegmentTrap extends Manager implements Factory
{
    public static function instance(): SegmentTrap|SegmentFake
    {
        return app(SegmentTrap::class);
    }

    public function create(): SyncDriver
    {
        return new SyncDriver();
    }

    public function getDefaultDriver(): string
    {
        /** @var string|null $driver */
        $driver = $this->config->get('segment.default');

        if (! is_string($driver)) {
            throw new InvalidArgumentException('A default SegmentTrap driver has not been configured');
        }

        return $driver;
    }

    /**
     * Parse the driver name.
     */
    protected function parseDriver(string $driver = null): string
    {
        return $driver ??= $this->getDefaultDriver();
    }

    /**
     * @param  string|null  $driver
     */
    public function driver($driver = null): Driver
    {
        $driver = $this->parseDriver($driver);

        return $this->drivers[$driver] ??= $this->resolve($driver);
    }

    public function forgetDriver(string $driver = null): static
    {
        $driver = $this->parseDriver($driver);
        unset($this->drivers[$driver]);

        return $this;
    }

    /**
     * Resolve the given segment driver instance by name.
     *
     * @param  string  $name
     *
     * @throws \InvalidArgumentException
     */
    protected function resolve($name): Driver
    {
        $config = $this->configurationFor($name);

        if (isset($this->customCreators[$name])) {
            /** @phpstan-ignore-next-line */
            return $this->callCustomCreator($name);
        }

        $driverMethod = 'create'.ucfirst($name).'Driver';

        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($config);
        }

        throw new InvalidArgumentException("Driver [{$name}] is not supported.");
    }

    /**
     * @return array<string, mixed>
     */
    public function configurationFor(string $driver = null): array
    {
        $driver ??= $this->getDefaultDriver();

        /** @var array<string, mixed> $optionsBase */
        $optionsBase = $this->config->get('segment.options', []);

        /** @var array<string, mixed> $optionsDriver */
        $optionsDriver = $this->config->get("segment.drivers.{$driver}", []);

        return array_replace($optionsBase, $optionsDriver);
    }

    public function createNullDriver(array $config): NullDriver
    {
        return new NullDriver($config);
    }

    public function createStackDriver(array $config): StackDriver
    {
        return new StackDriver($config);
    }

    public function createLogDriver(array $config): LogDriver
    {
        return new LogDriver($config);
    }

    public function createQueueDriver(array $config): QueueDriver
    {
        return new QueueDriver($config);
    }

    public function createSyncDriver(array $config): SyncDriver
    {
        return new SyncDriver($config);
    }

    public function createAfterDriver(array $config): AfterDriver
    {
        return new AfterDriver($config);
    }

    public function createEloquentDriver(array $config): EloquentDriver
    {
        return new EloquentDriver($config);
    }

    public function createFakeDriver(array $config): FakeDriver
    {
        return new FakeDriver($config);
    }

    public static function shutdown(): void
    {
        foreach (static::instance()->getDrivers() as $driver) {
            $driver->flush();
        }
    }

    public function pipeThroughModifiers(SegmentItem $item): SegmentItem
    {
        $pipeline = new Pipeline($this->container);
        $pipes = $this->config->get('segment.modifiers', []);

        /** @var SegmentItem $item */
        $item = $pipeline->send($item)->through($pipes)->thenReturn();

        return $item;
    }

    public static function applyModifiers(string $method, array $message): array
    {
        $item = new SegmentItem($method, $message);
        $item = static::instance()->pipeThroughModifiers($item);

        return [
            $item->method,
            $item->message,
        ];
    }
}
