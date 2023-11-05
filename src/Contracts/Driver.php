<?php

namespace SegmentTrap\Contracts;

interface Driver
{
    /**
     * @param  array<string, mixed>  $message
     */
    public function dispatch(string $method, array $message = []): bool;

    /**
     * Tracks a user action
     *
     * @param  array<mixed>  $message
     */
    public function track(array $message): bool;

    /**
     * Tags traits about the user.
     *
     * @param  array<mixed>  $message
     */
    public function identify(array $message): bool;

    /**
     * Tags traits about the group.
     *
     * @param  array<mixed>  $message
     */
    public function group(array $message): bool;

    /**
     * Tracks a page view.
     *
     * @param  array<mixed>  $message
     */
    public function page(array $message): bool;

    /**
     * Tracks a screen view.
     *
     * @param  array<mixed>  $message
     */
    public function screen(array $message): bool;

    /**
     * Aliases from one user id to another
     *
     * @param  array<mixed>  $message
     */
    public function alias(array $message): bool;

    /**
     * Flush any memory-ququed/stacked events that
     * may require submitting
     */
    public function flush(): bool;
}
