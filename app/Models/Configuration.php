<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $guarded = [];
    protected $casts = ['options' => 'array'];

    public static function findBySectionAndCountry($section, $country)
    {
        return static::where('section', $section)
            ->where('country', $country)
            ->first();
    }
}
