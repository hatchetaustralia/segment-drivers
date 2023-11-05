<?php

declare(strict_types=1);

return [

    'key' => '',

    /*
    |--------------------------------------------------------------------------
    | Default SegmentTrap Driver Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the SegmentTrap drivers below you wish to
    | use for submitting events to Segment API.
    |
    */

    'default' => env('SEGMENTTRAP_DEFAULT_DRIVER'),

    /*
    |--------------------------------------------------------------------------
    | SegmentTrap Drivers
    |--------------------------------------------------------------------------
    |
    | Here are each of the SegmentTrap drivers available for your application.
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
         * Dispatch the Segment events to the void
         */
        'eloquent' => [
            'model' => \SegmentTrap\Eloquent\SegmentEvent::class,
        ],

        /**
         * Dispatch the Segment events to the void
         */
        'null' => [
        ],
    ],

];
