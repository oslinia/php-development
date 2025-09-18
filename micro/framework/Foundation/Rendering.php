<?php

namespace Framework\Foundation;

use Framework\Frame;

class Rendering extends Frame
{
    private static array $buffer;

    public static function buffer(string $key): mixed
    {
        return self::$buffer[$key];
    }

    protected function set(string $key, mixed $value): void
    {
        self::$buffer[$key] = $value;
    }
}
