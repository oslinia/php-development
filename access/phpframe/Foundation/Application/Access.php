<?php

namespace PHPFrame\Foundation\Application;

use PHPFrame\Foundation\State;
use PHPFrame\Frame;

use function PHPFrame\Foundation\decrypt;
use function PHPFrame\Foundation\encrypt;

class Access extends View\Template
{
    private bool $bool = true;

    private string $root;

    private int $int;

    private string $nickname;

    private function token(string $filename): void
    {
        [$offset, $salt] = require $filename . '.php';

        $token = md5((microtime(true) - $offset) . $salt);

        file_put_contents($filename, $token);

        setcookie('token', encrypt($this->nickname . '.' . $token), path: '/');

        $this->bool = false;
    }

    private function root(string ...$args): string
    {
        return $this->root . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $args);
    }

    private function cookie(array $nicknames): void
    {
        [$nickname, $token] = explode('.', decrypt($_COOKIE['token']));

        if (isset($nicknames[$nickname])) {
            $filename = $this->root($nicknames[$nickname], 'token');

            if (
                is_file($filename)
                and
                State::$config['auth_inaction'] > (time() - filemtime($filename))
                and
                $token === file_get_contents($filename)
            ) {
                $this->nickname = $nickname;

                $this->token($filename);
            }
        }
    }

    private function verify(string $email, string $nickname): void
    {
        if (
            password_verify(
                $_POST['password'],
                require $this->root($email, 'password.php')
            )
        ) {
            if (
                $_POST['csrf'] === decrypt($_COOKIE['csrf'])
            ) {
                setcookie('csrf', '', 0, '/');

                $this->nickname = $nickname;

                $this->token($this->root($email, 'token'));
            } else {
                $this->int = 1;
            }
        }
    }

    private function post(array $nicknames): void
    {
        $username = $_POST['username'];

        if (
            false == filter_var($username, FILTER_VALIDATE_EMAIL)
        ) {
            !isset($nicknames[$username]) || $this->verify($nicknames[$username], $username);
        } else {
            $nicknames = array_flip($nicknames);

            !isset($nicknames[$username]) || $this->verify($username, $nicknames[$username]);
        }
    }

    public function auth(): bool
    {
        $this->root = new Frame()->root('resource', 'auth');

        $nicknames = require $this->root . '/nickname.php';

        (
            'POST' === $_SERVER['REQUEST_METHOD']
            and
            isset($_POST['username'])
            and
            isset($_POST['password'])
            and
            isset($_COOKIE['csrf'])
        ) ?
            $this->post($nicknames)
            :
            !isset($_COOKIE['token']) || $this->cookie($nicknames);

        return $this->bool;
    }

    public function login(): array
    {
        $warning = property_exists($this, 'int') ? '<p class="text-danger">' . [
            1 => 'There was an authorization cookie error.' .
                ' You may have multiple browser tabs open.' .
                ' Please try logging in again.'
        ][$this->int] . '</p>' : '';

        return parent::render_template('auth/login.php', ['warning' => $warning]);
    }
}
