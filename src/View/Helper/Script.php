<?php

namespace App\View\Helper;

class Script extends AbstractContainer
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __toString()
    {
        $result = '';
        foreach ($this->container as $item) {
            if (!is_array($item)) {
                $item = (array) $item;
            }

            // Third argument was optional
            if (!isset($item[2])) {
                $item[2] = 'text/javascript';
            }

            if (!empty($item[0])) {
                $result .= sprintf('<script type="%s" src="%s"></script>' . PHP_EOL, $item[2], $item[0]);
            } elseif (!empty($item[1])) {
                $result .= sprintf('<script type="%s">%s</script>'. PHP_EOL, $item[2], $item[1]);
            }
        }

        return $result;
    }
}
