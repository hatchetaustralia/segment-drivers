<?php

declare(strict_types=1);

return [

    'key' => env('SEGMENT_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Default SegmentAnalytics Driver Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the SegmentAnalytics drivers below you wish to
    | use for submitting events to Segment API.
    |
    */

    'default' => env('SEGMENT_DRIVER', 'log'),

    /*
    |--------------------------------------------------------------------------
    | SegmentAnalytics Drivers
    |--------------------------------------------------------------------------
    |
    | Here are each of the SegmentAnalytics drivers available for your application.
    |
    */

    'drivers' => [
        /**
         * Dispatch the Segment events to various drivers
         */
        'stack' => [
            'drivers' => [
                'queue',
                'log',
            ],
        ],

        /**
         * Dispatch the Segment events immediately (not recommended)
         */
        'sync' => [
        ],

        /**
         * Dispatch the Segment events after the response
         */
        'after' => [
        ],

        /**
         * Dispatch the Segment events via a queue worker
         */
        'queue' => [

            /**
             * The name of the queue connection to add Segment jobs to
             */
            'connection' => env('SEGMENT_QUEUE_CONNECTION', 'default'),

            /**
             * The name of the queue to add Segment jobs to
             */
            'queue' => env('SEGMENT_QUEUE_NAME', 'default'),
        ],

        /**
         * Dispatch the Segment events to a log channel
         */
        'log' => [
            'channel' => env('SEGMENT_LOG_CHANNEL', 'default'),
        ],

        /**
         * Dispatch the Segment events to a model/the DB.
         */
        'eloquent' => [
            'model' => \Hatchet\Segment\Eloquent\SegmentEvent::class,
        ],

        /**
         * Dispatch the Segment events to the void
         */
        'null' => [
        ],
    ],

    'relay' => [
        'middleware' => [
            // 'web',
        ],
    ],

    'modifiers' => [
        \Hatchet\Segment\Modifiers\SegmentIdentifyDefaults::class,
        \Hatchet\Segment\Modifiers\SegmentContextIdentifyEvents::class,
        \Hatchet\Segment\Modifiers\SegmentContextIpAddress::class,
        \Hatchet\Segment\Modifiers\SegmentContextEnvironment::class,
    ],

    'events' => [
        /**
         * Automatically fire authentication events from the backend
         */
        'auth' => false,
    ],
];
