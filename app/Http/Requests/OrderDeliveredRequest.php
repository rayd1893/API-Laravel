<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderDeliveredRequest extends FormRequest
{
    use InteractsWithPubSub;

    public function rules()
    {
        return [];
    }
}
