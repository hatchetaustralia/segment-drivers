<?php

namespace SegmentTrap\Drivers;

use SegmentTrap\Contracts\Driver;

abstract class AbstractDriver implements Driver
{
    /**
     * @param  array<string, mixed>  $config
     */
    public function __construct(protected array $config = [])
    {
    }

    /**
     * Get the config
     *
     * @return array<string, mixed>
     */
    public function config(): array
    {
        return $this->config;
    }

    /**
     * Tracks a user action
     *
     * @param  array<mixed>  $message
     */
    public function track(array $message): bool
    {
        $this->dispatch('track', $message);

        return true;
    }

    /**
     * Tags traits about the user.
     *
     * @param  array<mixed>  $message
     */
    public function identify(array $message): bool
    {
        $this->dispatch('identify', $message);

        return true;
    }

    /**
     * Tags traits about the group.
     *
     * @param  array<mixed>  $message
     */
    public function group(array $message): bool
    {
        $this->dispatch('group', $message);

        return true;
    }

    /**
     * Tracks a page view.
     *
     * @param  array<mixed>  $message
     */
    public function page(array $message): bool
    {
        $this->dispatch('page', $message);

        return true;
    }

    /**
     * Tracks a screen view.
     *
     * @param  array<mixed>  $message
     */
    public function screen(array $message): bool
    {
        $this->dispatch('screen', $message);

        return true;
    }

    /**
     * Aliases from one user id to another
     *
     * @param  array<mixed>  $message
     */
    public function alias(array $message): bool
    {
        $this->dispatch('alias', $message);

        return true;
    }

    public function __destruct()
    {
    }
}
