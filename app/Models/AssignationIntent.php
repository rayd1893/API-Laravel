<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignationIntent extends Model
{
    use InteractsWithUuid;

    protected $guarded = [];
    protected $dates = ['take_until'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    public function hasGhostCourier()
    {
        return $this->status == 'GHOSTED';
    }

    public function confirmAssignation()
    {
        $this->order->update(['assigned_to' => $this->courier_id, 'status' => Order::ACCEPTED_STATUS]);
        $this->update(['status' => 'ACCEPTED']);
    }

    public function reject()
    {
        $this->update(['status' => 'REJECTED']);
    }
}
