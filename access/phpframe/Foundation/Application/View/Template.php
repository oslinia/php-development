<?php

namespace PHPFrame\Foundation\Application\View;

use PHPFrame\Foundation\View\Buffer;
use PHPFrame\Frame\Wrapper;

use function PHPFrame\Foundation\View\buffer;

class Template extends Wrapper
{
    public function render_template(
        string      $name,
        array|null  $context = null,
        int|null    $code = null,
    ): array {
        Buffer::$file = __DIR__ . DIRECTORY_SEPARATOR . $name;
        Buffer::$context = $context ?? [];

        return [buffer(), $code, 'text/html'];
    }
}
