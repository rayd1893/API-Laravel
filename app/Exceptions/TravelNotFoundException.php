<?php

namespace App\Exceptions;

use RuntimeException;

class TravelNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Failed to consult travel with the given route.', 404);
    }
}
