<?php

spl_autoload_register(function ($class) {
    $path = explode('\\', $class);

    require match (array_shift($path)) {
        'Application' => implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'src', 'application', ...$path]),
        'PHPFrame' => implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'phpframe', ...$path]),
    } . '.php';
});
