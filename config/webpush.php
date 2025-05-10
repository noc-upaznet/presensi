<?php

return [
    'vapid' => [
        'subject' => 'mailto:your@email.com',
        'public_key' => env('VAPID_PUBLIC_KEY'),
        'private_key' => env('VAPID_PRIVATE_KEY'),
    ],
];