<?php

namespace App\Middleware;

use Slim\Middleware\Flash as BaseFlash;

class Flash extends BaseFlash
{
    /**
     * {@inheritDoc}
     */
    public function loadMessages()
    {
        $container = $this->app->session;
        $key       = $this->settings['key'];
        if (isset($container->$key)) {
            $this->messages['prev'] = $container->$key;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function save()
    {
        $container = $this->app->session;
        $key       = $this->settings['key'];

        $container->$key = $this->messages['next'];
    }
}
