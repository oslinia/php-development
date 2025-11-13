<?php

namespace Framework\Foundation\Administration\Access;

use function Framework\Foundation\salt_encrypt;

class User extends Validation
{
    public bool $bool = false;

    private string $dirname;

    private function file_write(string $filename, mixed $data): void
    {
        file_put_contents($filename, '<?php return ' . var_export($data, true) . ';' . PHP_EOL);
    }

    private function nickname(array $nicknames, string $dirname): void
    {
        $nicknames[$_POST['nickname']] = $_POST['email'];

        $this->file_write($dirname . 'nicknames.php', $nicknames);

        $this->bool = true;
    }

    private function generate_token(): string
    {
        $this->file_write($this->dirname . 'token.php', [
            $offset = 9999 + mt_rand() / mt_getrandmax() * (1000000 - 9999),
            $salt = bin2hex(random_bytes(16)),
        ]);

        return md5((microtime(true) - $offset) . $salt);
    }

    private function auth(): void
    {
        $token = $this->generate_token();

        file_put_contents($this->dirname . 'token', $token);

        setcookie('csrf', '', 0, '/');

        setcookie('token', salt_encrypt($_POST['nickname'] . '.' . $token), path: '/');
    }

    private function password_hash(): void
    {
        $this->file_write($this->dirname . 'password.php', password_hash(
            $_POST['password'],
            PASSWORD_DEFAULT,
        ));
    }

    private function mkdir(string $dirname): void
    {
        $dirname = $dirname . $_POST['email'];

        mkdir($dirname);

        $this->dirname = $dirname . DIRECTORY_SEPARATOR;
    }

    private function add(string $dirname, array $nicknames): void
    {
        $this->mkdir($dirname);

        $this->password_hash();

        $this->auth();

        $this->nickname($nicknames, $dirname);
    }

    private function post_not_empty(): bool
    {
        foreach (
            [
                'nickname',
                'email',
                'password',
                'confirm',
            ] as $name
        ) {
            if ('' === $_POST[$name]) {
                return false;
            }
        }

        return true;
    }

    public function __construct()
    {
        if (
            'POST' === $_SERVER['REQUEST_METHOD']
            and
            $this->post_not_empty()
        ) {
            $dirname = parent::root('resource', 'user', '');

            $nicknames = require $dirname . 'nicknames.php';

            parent::valid_nickname($nicknames);
            parent::valid_email($dirname);
            parent::valid_password();

            array() !== $this->warning || $this->add($dirname, $nicknames);
        }
    }
}
