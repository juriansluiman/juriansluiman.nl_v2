<?php

namespace App\View\Helper;

class Title extends AbstractContainer
{
    protected $separator;

    public function __construct($separator)
    {
        parent::__construct();

        $this->separator = $separator;
    }

    public function __toString()
    {
        return implode($this->separator, $this->container);
    }
}
