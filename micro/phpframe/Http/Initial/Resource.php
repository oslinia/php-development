<?php

namespace PHPFrame\Http\Initial;

use PHPFrame\Foundation\Routing\Reload;
use PHPFrame\Foundation\State;
use PHPFrame\Frame;

class Resource extends Frame
{
    private function resource_routing(string $dirname): void
    {
        is_dir($dirname) || mkdir($dirname);

        new Reload(
            parent::root('src', 'rules.php'),
            $dirname . DIRECTORY_SEPARATOR,
        );

        new State(
            parent::root('resource', 'config.php'),
            parent::root('resource', 'routing', 'map.php')
        );
    }

    private function resource(string $dirname): void
    {
        is_dir($dirname) || mkdir($dirname);

        $filename = $dirname . '/config.php';

        is_file($filename) || file_put_contents($filename, '<?php return ' . var_export(
            [
                'url_path' => '/static/',
            ],
            true
        ) . ';' . PHP_EOL);
    }

    public function __construct(string $filename)
    {
        parent::__construct(dirname($filename, 2));

        $this->resource(parent::root('resource'));

        $this->resource_routing(parent::root('resource', 'routing'));
    }
}
