<?php

namespace App\Mail;

use Slim\View;
use Mailgun\Mailgun;

class EmailService
{
    protected $view;
    protected $transport;
    protected $config;

    public function __construct(View $view, Mailgun $transport, array $config)
    {
        $this->view      = $view;
        $this->transport = $transport;
        $this->config    = $config;
    }

    public function send($template, $data, $options)
    {
        $config = $this->config;
        $html   = $this->view->fetch($template, $data + ['layout' => $template]);

        return $this->transport->sendMessage($config['domain'], [
            'from'       => $config['from'],
            'to'         => $options['to'],
            'subject'    => $options['subject'],
            'html'       => $html,
            'o:tracking' => 'no',
        ]);
    }
}
