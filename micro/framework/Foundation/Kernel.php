<?php

namespace Framework\Foundation;

use Framework\Foundation\Setting\Tools;
use Framework\Frame;

class Kernel extends Frame
{
    public array $data;
    private array $map;

    private function tools(string $filename): void
    {
        is_file($filename) || Tools::default($filename);

        $this->data = require $filename;
    }

    public function __construct(string $mapping, string $path_info)
    {
        $this->map = require $mapping . 'map.php';

        $this->tools(parent::root('resource', 'cache', 'tools.php'));

        $this->data['path_info'] = $path_info;

        $GLOBALS['_FW'] = $this;
    }

    public function collect(string $name, array $args): null|string
    {
        if (isset($this->map[$name])) {
            $route = $this->map[$name];

            $size = count($args);

            if (isset($route[$size])) {
                [$path, $pattern] = $route[$size];

                foreach ($args as $mask => $value)
                    $path = str_replace('{' . $mask . '}', $value, $path);

                if (preg_match($pattern, $path, $matches))
                    return $matches[0];
            }
        }

        return null;
    }
}
