<?php

namespace SegmentTrap\Http\Requests;

class SegmentPageRequest extends AbstractSegmentRequest
{
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:1000'],
            'category' => ['nullable', 'string', 'max:1000'],
            'properties' => ['nullable', 'array', 'max:1000'],
        ];
    }
}
