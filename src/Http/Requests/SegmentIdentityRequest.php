<?php

namespace SegmentTrap\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SegmentIdentityRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'guard' => ['nullable', 'string'],
        ];
    }
}
