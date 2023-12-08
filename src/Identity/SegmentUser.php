<?php

namespace SegmentTrap\Identity;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SegmentTrap\Facades\Segment;

class SegmentUser
{
    protected static ?SegmentUser $session = null;

    public ?Authenticatable $user = null;

    protected array $events = [];

    protected array $last = [];

    protected static ?SessionManager $store = null;

    public function __construct()
    {
        $this->events['userId'] ??= fn () => Auth::id();
        $this->events['anonymousId'] ??= fn () => self::sessionStore()->remember('segment::anonymousId', fn () => (string) Str::ulid());
    }

    public static function sessionStore(): SessionManager
    {
        return static::$store ??= session(); /** @phpstan-ignore-line */
    }

    public static function session(): SegmentUser
    {
        /** @phpstan-ignore-next-line */
        $existing = (array) self::sessionStore()->get('segment::user', []);

        return self::$session ??= (new SegmentUser())->withSessionData($existing);
    }

    public function withSessionData(array $data): self
    {
        $this->last = $data;

        return $this;
    }

    public function store(): static
    {
        $data = $this->data();

        if ($this->last !== $data) {
            $this->last = $data;
            self::sessionStore()->put('segment::user', $data);

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
