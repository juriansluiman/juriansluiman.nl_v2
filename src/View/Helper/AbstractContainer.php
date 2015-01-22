<?php

namespace App\View\Helper;

abstract class AbstractContainer
{
    protected $container;

    public function __construct()
    {
        $this->container = array();
    }

    public function append($value)
    {
        if (func_num_args() > 1) {
            $value = func_get_args();
        }

        array_push($this->container, $value);

        return $this;
    }

    public function prepend($value)
    {
        if (func_num_args() > 1) {
            $value = func_get_args();
        }

        array_unshift($this->container, $value);

        return $this;
    }
}
