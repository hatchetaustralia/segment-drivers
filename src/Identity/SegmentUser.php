<?php

namespace SegmentTrap\Identity;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SegmentTrap\Facades\Segment;

class SegmentUser
{
    protected static ?SegmentUser $session = null;

    public ?Authenticatable $user = null;

    protected array $events = [];

    public function __construct(protected array $last = [])
    {
        $this->events['userId'] ??= fn () => Auth::id();
        $this->events['anonymousId'] ??= fn () => session()->remember('segment::anonymousId', fn () => Str::random(16));
    }

    public static function session(): static
    {
        $existing = session()->get('segment::user', []);

        return self::$session ??= new static($existing);
    }

    public function store(): static
    {
        $data = $this->data();

        if ($this->last !== $data) {
            $this->last = $data;
            session()->put('segment::user', $data);

            Segment::driver()->identify($data);
        }

        return $this;
    }

    public function set(Authenticatable $user = null): static
    {
        $this->user = $user;

        $this->fire('change');
        $this->fire($user ? 'identified' : 'unidentified');

        $this->store();

        return $this;
    }

    public function common(): array
    {
        return [
            'userId' => $this->fire('userId'),
            'anonymousId' => $this->fire('anonymousId'),
        ];
    }

    public function data(): array
    {
        return $this->common() + [
            'traits' => (array) $this->fire('traits'),
        ];
    }

    public function get(): ?Authenticatable
    {
        return $this->user;
    }

    public function on(string $event, Closure $callback): static
    {
        $this->events[$event] = $callback;

        return $this;
    }

    public function fire(string $event, mixed $default = null): mixed
    {
        $callback = $this->events[$event] ?? null;

        return $callback ? $callback($this->get(), $this) : $default;
    }
}
