<?php

namespace Framework\Rendering;

use Framework\Foundation\Rendering;

class Template extends Rendering
{
    public static function is_file(string $name): bool
    {
        return is_file(parent::root('src', 'view', $name));
    }

    public function __construct(string $name)
    {
        parent::set('file', parent::root('src', 'view', $name));
    }

    public function file_exists(): bool
    {
        return is_file(parent::buffer('file'));
    }

    public function extract(array|null $context): void
    {
        parent::set('context', $context ?? []);
    }
}
