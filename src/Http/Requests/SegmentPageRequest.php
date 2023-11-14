<?php

namespace SegmentTrap\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SegmentPageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'category' => ['required', 'string'],
            'properties' => ['nullable', 'array'],
        ];
    }
}
