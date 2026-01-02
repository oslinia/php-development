<?php

namespace PHPFrame\Frame;

class Wrapper
{
    public function redirect_response(string $url, int $code = 307): array
    {
        header('Location: ' . $url);

        return ['', $code, null, 'ASCII'];
    }

    public function not_found(): array
    {
        return ['Not Found', 404, null, 'ASCII'];
    }

    public function base_response(
        string      $body,
        int|null    $code = null,
        null|string $mimetype = null,
        null|string $encoding = null,
    ): array {
        return [$body, $code, $mimetype, $encoding];
    }
}
