<?php

include 'vendor/autoload.php';

$app = new Slim\Slim(include 'app/config.php');

include 'app/services.php';
include 'app/hooks.php';
include 'app/controllers.php';

$app->run();
