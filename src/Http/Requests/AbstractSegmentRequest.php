<?php

namespace Hatchet\Segment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

abstract class AbstractSegmentRequest extends FormRequest
{
    public function verify(): void
    {
        $data = $this->json();
        $data = json_encode($data);

        if (mb_strlen($data) > 1_000_000) {
            throw new BadRequestException('Invalid payload - exceeds payload constraints');
        }
    }
}
