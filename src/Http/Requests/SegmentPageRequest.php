<?php

namespace SegmentTrap\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SegmentPageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string'],
            'category' => ['nullable', 'string'],
            'properties' => ['nullable', 'array'],
        ];
    }
}
