<?php

namespace PHPFrame\Frame;

use PHPFrame\Foundation\View\Template;

use function PHPFrame\Foundation\View\buffer;

class View extends Wrapper
{
    public function template_exists(string $name): bool
    {
        return Template::is_file($name);
    }

    public function render_template(
        string      $name,
        array|null  $context = null,
        int|null    $code = null,
        null|string $mimetype = null,
        null|string $encoding = null,
    ): array {
        $template = new Template($name);

        if ($template->file_exists()) {
            $template->extract($context);

            return [buffer(), $code, $mimetype ?? 'text/html', $encoding];
        }

        return ['Template not found', 500, null, 'ASCII'];
    }
}
