<?php

return [
    'core' => [
        'key' => env('CORE_API_KEY'),
        'assignations' => env('COURIERS_ASSIGNATIONS_SERVICE'),
        'assignment_intent_notification' => env('COURIERS_NOTIFICATIONS_SERVICE'),
    ],
    'couriers_tracking' => env('COURIERS_TRACKING_SERVICE'),
    'directions' => env('DIRECTIONS_SERVICE'),
];
