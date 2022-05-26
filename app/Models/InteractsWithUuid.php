<?php

namespace App\Models;

use Illuminate\Support\Str;

trait InteractsWithUuid
{
    public static function findOrFailByUuid($uuid)
    {
        return static::where('uuid', $uuid)->firstOrFail();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }
}
