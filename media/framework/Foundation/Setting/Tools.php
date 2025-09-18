<?php

namespace Framework\Foundation\Setting;

class Tools
{
    public static function default(string $filename): void
    {
        $f = fopen($filename, 'w');

        fwrite($f, '<?php return ' . var_export([
            'dirname' => dirname($filename),
            'public' => '/static/',
        ], true) . ';');

        fclose($f);
    }
}
