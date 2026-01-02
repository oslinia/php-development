<?php

namespace PHPFrame\Http;

use PHPFrame\Foundation\State;
use PHPFrame\Frame;
use PHPFrame\Frame\Path;

class Request extends Frame
{
    public function callback(string $dirname): array|string
    {
        foreach (require $dirname . 'patterns.php' as $pattern => $items)
            if (preg_match($pattern, State::$path_info, $matches)) {
                [$name, $class] = $items;

                $method = explode('.', $name, 2)[1] ?? '__invoke';

                $value = array_slice($matches, 1);

                $masks = (require $dirname . 'masks.php')[$name];

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

    public function __construct(string $dirname)
    {
        parent::__construct(dirname($dirname) . DIRECTORY_SEPARATOR);

        new State(
            parent::root('resource', 'config.php'),
            parent::root('resource', 'routing', 'map.php')
        );

        new Response($this->callback(parent::root('resource', 'routing', '')));
    }
}
