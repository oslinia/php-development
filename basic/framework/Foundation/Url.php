<?php

namespace Framework\Foundation;

class Url extends Routing\Mapper
{
    public function scheme(): string
    {
        return (
            isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] && '443' === $_SERVER['SERVER_PORT']
        ) ? 'https' : 'http';
    }

    public function host(): string
    {
        return $_SERVER['HTTP_HOST'];
    }

    public function path(): string
    {
        return parent::$map->url[0];
    }

    public function query(): string
    {
        return parent::$map->url[1] ?? '';
    }
}
