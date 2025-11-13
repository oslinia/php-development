<?php

namespace Framework\Foundation;

use Framework\Frame;

class Env extends Frame
{
    public array $env;

    private function default(string $filename): void
    {
        $f = fopen($filename, 'w');

        fwrite($f, '<?php return ' . var_export([
            'dirname' => dirname($filename),
            'public' => '/static/',
            'salt' => bin2hex(random_bytes(16)),
            'inaction' => 3600,
        ], true) . ';');

        fclose($f);
    }

    private function init(string $filename): void
    {
        is_file($filename) || $this->default($filename);

        $this->env = require $filename;
    }

    public function __construct(string $path_info)
    {
        $this->init(parent::root('resource', 'config.php'));

        $this->env['path_info'] = $path_info;
    }
}
