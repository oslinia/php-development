<?php

namespace Framework\Rendering;

use Framework\Foundation\Rendering;

class Media extends Rendering
{
    public static function is_file(string $name): bool
    {
        return is_file(parent::root('resource', 'media', $name));
    }

    public function __construct(string $name)
    {
        parent::set('file', parent::root('resource', 'media', $name));
    }

    public function file_exists(): bool
    {
        parent::set('bool', is_file(parent::buffer('file')));

        return parent::buffer('bool');
    }

    public function file(): string
    {
        return parent::buffer('file');
    }
}
