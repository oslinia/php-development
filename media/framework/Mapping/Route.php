<?php

namespace Framework\Mapping;

use Framework\Foundation\Kernel;
use Framework\Frame;
use Framework\Frame\Path;

class Route extends Frame
{
    private string $dirname;

    private function cache(string $dirname): void
    {
        is_dir($dirname) || new Structure(
            $dirname,
            parent::root('resource', 'routes.php'),
        );

        $this->dirname = $dirname . DIRECTORY_SEPARATOR;
    }

    public function __construct(string $dirname)
    {
        parent::__construct($dirname);

        $this->cache(parent::root('resource', 'cache', 'mapping'));
    }

    public function callback(string $path_info): array|string
    {
        new Kernel($this->dirname, $path_info);

        foreach (require $this->dirname . 'patterns.php' as $pattern => $items)
            if (preg_match($pattern, $path_info, $matches)) {
                [$name, $class] = $items;

                $method = explode('.', $name, 2)[1] ?? '__invoke';

                $value = array_slice($matches, 1);

                $masks = (require $this->dirname . 'masks.php')[$name];

                if (0 < $size = count($value)) {
                    if (isset($masks[$size])) {
                        $tokens = array();

                        foreach ($value as $i => $pattern)
                            $tokens[$masks[$size][$i]] = $pattern;

                        return new $class()->$method(new Path($tokens));
                    }
                } else {
                    return new $class()->$method();
                }
            }

        return ['Not Found', 404, null, 'ASCII'];
    }
}
