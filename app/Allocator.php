<?php

namespace App;

use App\Services\CoreService;
use App\Models\AssignationIntent;

class Allocator
{
    protected AssignationIntent $intent;

    public function __construct($intent)
    {
        $this->intent = $intent;
    }

    public function assign($acceptance): bool
    {
        if (!now()->lte($this->intent->take_until)) {
            return false;
        }

        if ($acceptance) {
            $this->intent->confirmAssignation();
            CoreService::assignOrder($this->intent->order, $this->intent->courier);
            $assigned = true;
        } else {
            $this->intent->reject();
        }

        return $assigned ?? false;
    }
}
