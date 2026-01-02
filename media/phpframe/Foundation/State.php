<?php

namespace PHPFrame\Foundation;

class State
{
    public static string $path_info;

    public static array $config;

    private static array $map;

    public function __construct(string $path_to_config, string $path_to_map)
    {
        self::$path_info = explode('?', $_SERVER['REQUEST_URI'])[0];

        self::$config = require $path_to_config;
        self::$map = require $path_to_map;
    }

    public static function url(string $name, array $args): null|string
    {
        if (isset(self::$map[$name])) {
            $route = self::$map[$name];

            $size = count($args);

            if (isset($route[$size])) {
                [$path, $pattern] = $route[$size];

                foreach ($args as $mask => $value) {
                    $path = str_replace('{' . $mask . '}', $value, $path);
                }

                if (preg_match($pattern, $path, $matches)) {
                    return $matches[0];
                }
            }
        }

        return null;
    }
}
