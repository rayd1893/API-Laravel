<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFareRequest extends FormRequest
{
    public function rules()
    {
        return [
            'origin' => ['required', 'array'],
            'destination' => ['required', 'array'],
            'country' => ['required', 'string', 'max:254'],
            'userid' => ['nullable', 'string', 'max:254'],
        ];
    }
}
