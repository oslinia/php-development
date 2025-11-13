<?php

namespace Framework;

use function Framework\Foundation\salt_encrypt;

function csrf_token(): string
{
    $token = md5(microtime() . $GLOBALS['_FW']->env['salt']);

    setcookie('csrf', salt_encrypt($token), path: '/');

    return $token;
}

function url_path(string $name): string
{
    return $GLOBALS['_FW']->env['public'] . $name;
}

function path_info(): string
{
    return $GLOBALS['_FW']->env['path_info'];
}

function url_for(string ...$args): null|string
{
    return $GLOBALS['_FW']->collect(array_shift($args), $args);
}

class Frame
{
    private static string $dirname;

    public function __construct(string $dirname)
    {
        self::$dirname = $dirname . DIRECTORY_SEPARATOR;
    }

    protected function root(string ...$args): string
    {
        return self::$dirname . implode(DIRECTORY_SEPARATOR, $args);
    }
}
