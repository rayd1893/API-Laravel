<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const DELIVERED_STATUS = 6;
    public const CANCELLED_STATUS = 7;
    public const ACCEPTED_STATUS = 100;
    public const ASSIGNING_STATUS = 120;

    protected $guarded = [];
    protected $casts = ['origin' => 'array', 'destination' => 'array'];
    protected $dates = ['next_assignation_at'];

    public static function findByCounter($counter)
    {
        return static::where('counter', $counter)->first();
    }

    public function getNextAssignationAtAttribute()
    {
        $assignation = optional($this->assignationIntents()->orderBy('id', 'DESC')->first());

        return $assignation->take_until;
    }

    public function scopeUnassigneds($query)
    {
        return $query->where('status', self::ASSIGNING_STATUS);
    }

    public function assignationIntents()
    {
        return $this->hasMany(AssignationIntent::class);
    }

    public function cancel()
    {
        $this->update(['status' => self::CANCELLED_STATUS]);
    }

    public function addSnapshot($snapshot)
    {
        return $snapshot->map(function ($couriers) {
            $intent = ['order_id' => $this->getKey()];

            if (array_key_exists('is_ghost', $couriers)) {
                $intent = array_merge($intent, [
                    'courier_id' => null,
                    'status' => 'GHOSTED',
                ]);
            } else {
                $intent = array_merge($intent, [
                    'courier_id' => $couriers['courier']->getKey(),
                    'status' => 'IGNORED',
                ]);
            }

            return AssignationIntent::create($intent);
        });
    }

    public function snapshotsCout()
    {
        $count = $this->assignationIntents->chunk(config('assignations.snapshot_size'))
            ->count();

        return $count;
    }

    public function inTime()
    {
        $nextAssignation = $this->next_assignation_at;

        return $nextAssignation != null ? $nextAssignation->isBefore(now()) : false;
    }

    public function isAssignable()
    {
        return $this->status == Order::ASSIGNING_STATUS;
    }
}
