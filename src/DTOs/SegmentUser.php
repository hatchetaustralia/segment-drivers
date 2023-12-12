<?php

namespace Hatchet\Segment\DTOs;

use Hatchet\Segment\Facades\Segment;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Contracts\Support\Arrayable;
use Psr\Log\LoggerInterface;
use Throwable;

class SegmentUser implements Arrayable
{
    protected ?Authenticatable $user = null;

    public array $message = [];

    public function __construct(
        public readonly LoggerInterface $logger,
        public readonly Auth $auth,
    ) {
    }

    public function user(): ?Authenticatable
    {
        return $this->user;
    }

    public function found(): bool
    {
        return $this->user !== null;
    }

    public function loadMissing(string $guard = null): static
    {
        if ($this->user !== null) {
            return $this;
        }

        return $this->load($guard);
    }

    public function load(string $guard = null): static
    {
        $this->set($this->auth->guard($guard)->user());

        return $this;
    }

    public function set(mixed $user): static
    {
        if ($user instanceof Authenticatable || is_null($user)) {
            $this->user = $user;

            return $this;
        }

        if (is_string($user) || is_int($user)) {
            try {
                $class = config('segment.auth.model');

                $this->user = $class::findOrFail($user);
            } catch (Throwable $e) {
                $this->logger->error('segment auth model lookup failed ', [
                    'id' => $user,
                ]);

                report($e);
            }

            return $this;
        }

        // invalid type

        return $this;
    }

    public function toArray(): array
    {
        return $this->message;
    }

    public static function make(): static
    {
        return app()->make(SegmentUser::class);
    }

    public function withDefaults(array $data): static
    {
        $this->message = array_replace_recursive($data, $this->message);

        return $this;
    }

    public function withOverrides(array $data): static
    {
        $this->message = array_replace_recursive($this->message, $data);

        return $this;
    }

    public static function build(): static
    {
        Segment::driver('null')->identify([]);

        return SegmentUser::make();
    }

    public static function identify(): static
    {
        $old = session()->get('segment_identify_last', []);
        $new = SegmentUser::build()->toArray();

        if ($new !== $old) {
            Segment::identify($new);

            session()->put('segment_identify_last', $new);
        }

        return static::make();
    }

    public function common(): array
    {
        return collect($this->message)
            ->only([
                'userId',
                'anonymousId',
            ])
            ->all();
    }
}
