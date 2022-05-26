<?php

namespace App\Http\Requests;

trait InteractsWithPubSub
{
    public function data()
    {
        return json_decode(base64_decode($this->input('message')['data']), true);
    }
}
