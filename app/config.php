<?php

return [
    'view'   => 'Slim\LayoutView',
    'layout' => 'layout.phtml',
    'debug'  => false,
    'redis'  => [
        'socket' => '127.0.0.1',//'/tmp/redis.sock',
        'prefix' => 'jurian',
    ],
];
