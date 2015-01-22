<?php

namespace App\View\Helper;

class Meta extends AbstractContainer
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __toString()
    {
        $result = '';
        foreach ($this->container as $item) {
            if (!is_array($item) && !count($item) === 2) {
                continue;
            }

            $result .= sprintf('<meta name="%s" content="%s">' . PHP_EOL, $item[0], $item[1]);
        }

        return $result;
    }
}
