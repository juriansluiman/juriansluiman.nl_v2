<?php

$app->container->singleton('redis', function () use ($app) {
    $redis  = new Redis;
    $config = $app->config('redis');
    $redis->connect($config['socket']);

    return $redis;
});

$app->container->singleton('repository', function () use ($app) {
    $config     = $app->config('redis');
    return new App\Repository\ArticleRepository($app->redis, $config['prefix']);
});

$app->container->singleton('slugifier', function () use ($app) {
    return new BaconStringUtils\Slugifier(
        new BaconStringUtils\UniDecoder
    );
});

$app->container->singleton('session', function () use ($app) {
    $config = $app->config('session');
    return new Zend\Session\Container($config);
});

$app->container->singleton('email', function () use ($app) {
    $config    = $app->config('email');
    $transport = new Mailgun\Mailgun($config['api_key']);

    return new App\Mail\EmailService($app, $transport, $config);
});
