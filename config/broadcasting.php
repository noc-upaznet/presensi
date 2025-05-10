<?php

return [

    'default' => env('BROADCAST_CONNECTION', 'null'),

    'connections' => [

        // 'pusher' => [
        //     'driver' => 'pusher',
        //     'key' => env('PUSHER_APP_KEY'),
        //     'secret' => env('PUSHER_APP_SECRET'),
        //     'app_id' => env('PUSHER_APP_ID'),
        //     'options' => [
        //         'cluster' => env('PUSHER_APP_CLUSTER'),
        //         'useTLS' => false,
        //         'host' => env('PUSHER_HOST', '127.0.0.1'),
        //         'port' => env('PUSHER_PORT', 6001),
        //         'scheme' => env('PUSHER_SCHEME', 'http'),
        //     ],
        // ],
        'reverb' => [
            'driver' => 'reverb',
            'app_id' => env('REVERB_APP_ID'),
            'key' => env('REVERB_APP_KEY'),
            'secret' => env('REVERB_APP_SECRET'),
            'host' => env('REVERB_HOST'),
            'port' => env('REVERB_PORT'),
        ],

    ],

];