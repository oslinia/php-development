<?php

namespace Framework\Mapping;

class Structure extends Rule
{
    private function structurize(): array
    {
        $patterns = $masks = $map = array();

        foreach (parent::$rules as $path => $items) {
            [[$name, $class], $size] = $items;

            $pattern = '/^' . str_replace('/', '\/', $path) . '$/';

            $masks[$name][$size] = null;

            0 === $size || [$masks[$name][$size], $pattern] = [
                $items[2],
                str_replace(array_keys($items[3]), array_values($items[3]), $pattern),
            ];

            $patterns[$pattern] = [$name, $class];

            $map[$name][$size] = [$path, $pattern];
        }

        return ['patterns' => $patterns, 'masks' => $masks, 'map' => $map];
    }

    private function write(string $dirname): void
    {
        foreach ($this->structurize() as $name => $value) {
            $f = fopen($dirname . $name . '.php', 'w');

            fwrite($f, '<?php return ' . var_export($value, true) . ';');

            fclose($f);
        }
    }

    public function __construct(string $dirname, string $app_routes)
    {
        mkdir($dirname);

        require_once $app_routes;

        $this->write($dirname . DIRECTORY_SEPARATOR);
    }
}
