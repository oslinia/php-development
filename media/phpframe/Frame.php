<?php

namespace PHPFrame;

use PHPFrame\Foundation\State;

function path_info(): string
{
    return State::$path_info;
}

function url_for(string ...$args): null|string
{
    return State::url(array_shift($args), $args);
}

function url_path(string $name): string
{
    return State::$config['url_path'] . $name;
}

class Frame
{
    private static string $root;

    public function __construct(null|string $root = null)
    {
        null === $root || self::$root = $root;
    }

    public function root(string ...$args): string
    {
        return self::$root . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $args);
    }
}
