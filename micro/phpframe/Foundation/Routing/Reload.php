<?php

namespace PHPFrame\Foundation\Routing;

use PHPFrame\Routing\Rule;

class Reload extends Rule
{
    private function rules(): array
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

    public function __construct(string $rules, string $dirname)
    {
        require $rules;

        foreach ($this->rules() as $name => $value) {
            file_put_contents($dirname . $name . '.php', '<?php return ' . var_export(
                $value,
                true
            ) . ';' . PHP_EOL);
        }
    }
}
