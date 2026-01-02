<?php

namespace PHPFrame\Foundation\Application\Auth;

use function PHPFrame\Foundation\encrypt;

class Registration
{
    public array $warning = array();

    public bool $bool = false;

    private function nickname(array $nicknames, string $filename): void
    {
        $nicknames[$_POST['nickname']] = $_POST['email'];

        file_put_contents($filename, '<?php return ' . var_export(
            $nicknames,
            true
        ) . ';' . PHP_EOL);

        $this->bool = true;
    }

    private function cookies(string $token): void
    {
        setcookie('csrf', '', 0, '/');

        setcookie('token', encrypt($_POST['nickname'] . '.' . $token), path: '/');
    }

    private function params(string $filename): array
    {
        file_put_contents($filename, '<?php return ' . var_export(
            [
                9999 + mt_rand() / mt_getrandmax() * (1000000 - 9999),
                bin2hex(random_bytes(16))
            ],
            true
        ) . ';' . PHP_EOL);

        return require $filename;
    }

    private function token(string $dirname, string $filename): void
    {
        [$offset, $salt] = $this->params($dirname . 'token.php');

        $token = md5((microtime(true) - $offset) . $salt);

        file_put_contents($filename, $token);

        $this->cookies($token);
    }

    private function password(string $filename): void
    {
        file_put_contents($filename, '<?php return ' . var_export(
            password_hash(
                $_POST['password'],
                PASSWORD_DEFAULT
            ),
            true
        ) . ';' . PHP_EOL);
    }

    private function mkdir(string $dirname): void
    {
        $dirname .= DIRECTORY_SEPARATOR . $_POST['email'];

        mkdir($dirname);

        $dirname .= DIRECTORY_SEPARATOR;

        $this->password($dirname . 'password.php');

        $this->token($dirname, $dirname . 'token');
    }

    private function create(string $dirname, array $nicknames, string $filename): void
    {
        $this->mkdir($dirname);

        $this->nickname($nicknames, $filename);
    }

    private function not_empty(): bool
    {
        foreach (
            [
                'nickname',
                'email',
                'password',
                'confirm',
            ] as $key
        ) {
            if ('' === $_POST[$key]) {
                return false;
            }
        }

        return true;
    }

    public function __construct(string $dirname)
    {
        if (
            'POST' === $_SERVER['REQUEST_METHOD']
            and
            $this->not_empty()
        ) {
            $filename = $dirname . '/nickname.php';

            $nicknames = require $filename;

            new Validation($this->warning, $nicknames, $dirname);

            array() !== $this->warning || $this->create($dirname, $nicknames, $filename);
        }
    }
}
