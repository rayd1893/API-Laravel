<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fare extends Model
{
    use InteractsWithUuid;

    protected $guarded = [];
    protected $casts = ['price' => 'array'];
}
