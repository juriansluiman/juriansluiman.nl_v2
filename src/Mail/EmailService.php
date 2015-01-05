<?php

namespace App\Mail;

use Slim\Slim;
use Mailgun\Mailgun;

class EmailService
{
    protected $app;
    protected $transport;
    protected $config;

    public function __construct(Slim $app, Mailgun $transport, array $config)
    {
        $this->app       = $app;
        $this->transport = $transport;
        $this->config    = $config;
    }

    public function send($template, $data, $options)
    {
        $config = $this->config;
        $html   = $this->app->view->fetch($template, $data + ['layout' => $template]);

        return $this->transport->sendMessage($config['domain'], [
            'from'       => $config['from'],
            'to'         => $options['to'],
            'subject'    => $options['subject'],
            'html'       => $html,
            'o:tracking' => 'no',
        ]);
    }
}
