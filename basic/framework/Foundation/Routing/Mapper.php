<?php

namespace Framework\Foundation\Routing;

use Framework\Http\Path;

class Mapper
{
    protected static array $endpoint = array();
    protected static bool $flag;
    protected static array $tmp;
    protected static object $map;
    private string $routing;

    private function caching(string $routing): void
    {
        mkdir($routing);

        $patterns = $masks = $urls = array();

        foreach (self::$tmp as $path => $items) {
            [$name, $size] = $items;

            $pattern = '/^' . str_replace('/', '\/', $path) . '$/';

            $masks[$name][$size] = null;

            0 === $size || [$masks[$name][$size], $pattern] = [
                $items[2],
                str_replace(array_keys($items[3]), array_values($items[3]), $pattern),
            ];

            $patterns[$pattern] = $name;

            $urls[$name][$size] = [$path, $pattern];
        }

        foreach (['patterns' => $patterns, 'masks' => $masks, 'urls' => $urls] as $name => $value) {
            $f = fopen($this->routing . $name . '.php', 'w');
            fwrite($f, '<?php return ' . var_export($value, true) . ';');
            fclose($f);
        }
    }

    protected function init(string $routing, string $src): void
    {
        self::$flag = is_dir($routing);
        self::$flag || self::$tmp = [];

        require $src . 'app' . DIRECTORY_SEPARATOR . 'routing.php';

        $this->routing = $routing . DIRECTORY_SEPARATOR;

        self::$flag || $this->caching($routing);
    }

    protected function response(): array|string
    {
        self::$map = (object)[
            'url' => explode('?', $_SERVER['REQUEST_URI'], 2),
            'urls' => require $this->routing . 'urls.php',
        ];

        foreach (require $this->routing . 'patterns.php' as $pattern => $name)
            if (preg_match($pattern, self::$map->url[0], $matches))
                if (isset(self::$endpoint[$name])) {
                    [$class, $method, $middleware] = self::$endpoint[$name];

                    $value = array_slice($matches, 1);

                    $masks = (require $this->routing . 'masks.php')[$name];

                    if (0 < $size = count($value)) {
                        if (isset($masks[$size])) {
                            $tokens = array();

                            foreach ($value as $i => $pattern)
                                $tokens[$masks[$size][$i]] = $pattern;

                            array_unshift($middleware, new Path($tokens));

                            return new $class()->$method(...$middleware);
                        }
                    } else {
                        return new $class()->$method(...$middleware);
                    }
                }

        return ['Not Found', 404, null, 'ASCII'];
    }
}
