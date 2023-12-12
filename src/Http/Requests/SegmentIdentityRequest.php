<?php

namespace Hatchet\Segment\Http\Requests;

class SegmentIdentityRequest extends AbstractSegmentRequest
{
    public function rules(): array
    {
        return [
            'guard' => ['nullable', 'string'],
        ];
    }
}
