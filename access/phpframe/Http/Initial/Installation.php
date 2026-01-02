<?php

namespace PHPFrame\Http\Initial;

use PHPFrame\Foundation\Application\Auth\Registration;
use PHPFrame\Foundation\View\Buffer;

use function PHPFrame\Foundation\View\buffer;
use function PHPFrame\url_for;

class Installation extends Resource
{
    private function warning(array $warning): array
    {
        foreach ($warning as $key => $value) {
            $warning[$key] = '<span class="text-danger">' . $value . '</span>' . PHP_EOL;
        }

        return ['warning' => $warning];
    }

    private function registration(array $warning): string
    {
        Buffer::$file = implode(DIRECTORY_SEPARATOR, [
            dirname(__DIR__, 2),
            'Foundation',
            'Application',
            'View',
            'auth',
            'registration.php'
        ]);
        Buffer::$context = $this->warning($warning);

        return buffer();
    }

    private function response(array $warning): void
    {
        $body = $this->registration($warning);

        header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
        header('content-length: ' . strlen($body));
        header('content-type: text/html; charset=UTF-8');

        echo $body;
    }

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

        $auth = new Registration(parent::root('resource', 'auth'));

        $auth->bool ? $this->redirect($filename) : $this->response($auth->warning);
    }
}
