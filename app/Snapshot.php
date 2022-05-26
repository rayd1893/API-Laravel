<?php

namespace App;

use Illuminate\Support\Collection;

class Snapshot extends Collection
{
    public function fulfillWithGhostCouriers($until)
    {
        while ($this->count() < $until) {
            $this->add(['is_ghost' => true]);
        }
    }
}
