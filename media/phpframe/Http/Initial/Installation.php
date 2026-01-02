<?php

namespace PHPFrame\Http\Initial;

use function PHPFrame\url_for;

class Installation extends Resource
{
    private function rebuild(string $filename): void
    {
        file_put_contents(dirname($filename) . '/index.php', '<?php' . PHP_EOL . PHP_EOL .
            'use PHPFrame\Http\Request;' . PHP_EOL . PHP_EOL .
            "require dirname(__DIR__) . '/vendor/autoload.php';" . PHP_EOL . PHP_EOL .
            'new Request(__DIR__);' . PHP_EOL);

        unlink($filename);
    }

    private function redirect(string $filename): void
    {
        $this->rebuild($filename);

        header($_SERVER['SERVER_PROTOCOL'] . ' 307 Temporary Redirect');
        header('content-length: 0');
        header('content-type: text/plain; charset=ASCII');
        header('Location: ' . url_for('main'));
    }

    public function __construct(string $filename)
    {
        parent::__construct($filename);

        $this->redirect($filename);
    }
}
