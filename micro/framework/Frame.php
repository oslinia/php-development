<?php

namespace Framework;

use Framework\Rendering\Template;

function path_info(): string
{
    return $GLOBALS['_FW']->data['path_info'];
}

function query_string(): string
{
    return $_SERVER['QUERY_STRING'] ?? '';
}

function url_for(string ...$args): null|string
{
    $name = array_shift($args);

    return $GLOBALS['_FW']->collect($name, $args);
}

function url_path(string $name): string
{
    return $GLOBALS['_FW']->data['public'] . $name;
}

function redirect_response(string $url, int $code = 307): array
{
    header('Location: ' . $url);

    return ['', $code];
}

function base_response(
    string      $body,
    int|null    $code = null,
    null|string $mimetype = null,
    null|string $encoding = null,
): array {
    return [$body, $code, $mimetype, $encoding];
}

function template_exists(string $name): bool
{
    return Template::is_file($name);
}

function buffer(): string
{
    ob_start();

    extract(Template::buffer('context'));

    require Template::buffer('file');

    return ob_get_clean();
}

function render_template(
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

class Frame
{
    private static string $dirname;

    public function __construct(string $dirname)
    {
        self::$dirname = $dirname . DIRECTORY_SEPARATOR;
    }

    protected function root(string ...$args): string
    {
        return self::$dirname . implode(DIRECTORY_SEPARATOR, $args);
    }
}
