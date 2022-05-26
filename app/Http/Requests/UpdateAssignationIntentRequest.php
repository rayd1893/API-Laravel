<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssignationIntentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'accepted' => ['required', 'boolean'],
        ];
    }
}
