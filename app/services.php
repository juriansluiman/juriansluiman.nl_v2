<?php

$app->container->singleton('redis', function () use ($app) {
    $redis  = new Redis;
    $config = $app->config('redis');
    $redis->connect($config['socket']);

    return $redis;
});

$app->container->singleton('repository', function () use ($app) {
    $config     = $app->config('redis');
    $repository = new App\Repository\ArticleRepository($app->redis, $config['prefix']);

    return $repository;
});

$app->container->singleton('slugifier', function () use ($app) {
    return new BaconStringUtils\Slugifier(
        new BaconStringUtils\UniDecoder
    );
});
