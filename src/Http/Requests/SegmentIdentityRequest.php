<?php

namespace SegmentTrap\Http\Requests;

class SegmentIdentityRequest extends AbstractSegmentRequest
{
    public function rules(): array
    {
        return [
            'guard' => ['nullable', 'string'],
        ];
    }
}
