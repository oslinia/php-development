<?php

namespace Framework\Frame;

use Framework\Rendering\Template;

use function Framework\buffer;

class View extends Response
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
        $view = new Template($name);

        if ($view->file_exists()) {
            $view->extract($context);

            return [buffer(), $code, $mimetype ?? 'text/html', $encoding];
        }

        return ['Template not found', 500, null, 'ASCII'];
    }
}
