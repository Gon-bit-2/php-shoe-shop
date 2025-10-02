<?php
return [
    // Routes không cần authentication
    'public' => [
        '/',
        '/home',
        '/login',
        '/register'
    ],

    // Routes cần authentication
    'protected' => [
        '/dashboard',
        '/profile',
        '/orders',
        'history'
    ]
];
