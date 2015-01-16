<?php

$config = [
    'view'   => 'Slim\LayoutView',
    'layout' => 'layout.phtml',
    'debug'  => false,
    'redis'  => [
        'socket' => '127.0.0.1',
        'prefix' => '',
    ],
    'session' => '',
    'email'   => [
        'api_key' => '',
        'domain'  => '',
        'from'    => '',
    ],
    'auth' => [
        'to' => '',
    ],
    'search' => [
        'key' => '',
        'cx'  => '',
    ],
];

if (file_exists('app/config.local.php')) {
    $config = Zend\Stdlib\ArrayUtils::merge($config, include('app/config.local.php'));
}

return $config;
