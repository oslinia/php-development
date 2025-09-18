<?php

namespace Framework\Frame;

use Framework\Rendering\Media;

class Response
{
    public function path_info(): string
    {
        return $GLOBALS['_FW']->data['path_info'];
    }

    public function query_string(): string
    {
        return $_SERVER['QUERY_STRING'] ?? '';
    }

    public function url_for(string ...$args): null|string
    {
        $name = array_shift($args);

        return $GLOBALS['_FW']->collect($name, $args);
    }

    public function url_path(string $name): string
    {
        return $GLOBALS['_FW']->data['public'] . $name;
    }

    public function redirect_response(string $url, int $code = 307): array
    {
        header('Location: ' . $url);

        return ['', $code];
    }

    public function base_response(
        string      $body,
        int|null    $code = null,
        null|string $mimetype = null,
        null|string $encoding = null,
    ): array {
        return [$body, $code, $mimetype, $encoding];
    }

    public function media_exists(string $name): bool
    {
        return Media::is_file($name);
    }

    public function render_media(string $name, null|string $encoding = null): array
    {
        $media = new Media($name);

        if ($media->file_exists())
            return [$media->file(), $encoding];

        return ['File not found', 404, null, 'ASCII'];
    }
}
