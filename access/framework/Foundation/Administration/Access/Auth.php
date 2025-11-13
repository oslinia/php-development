<?php

namespace Framework\Foundation\Administration\Access;

use Framework\Frame;

use function Framework\Foundation\salt_decrypt;
use function Framework\Foundation\salt_encrypt;

class Auth extends Frame
{
    public bool $bool = true;
    public int $int;

    private string $email;
    private string $nickname;

    private function token(string $filename): void
    {
        [$offset, $salt] = require parent::root('resource', 'user', $this->email, 'token.php');

        $token = md5((microtime(true) - $offset) . $salt);

        file_put_contents($filename, $token);

        $token = $this->nickname . '.' . $token;

        setcookie('token', salt_encrypt($token), path: '/');

        $this->bool = false;
    }
    private function post(string $email, string $nickname): void
    {
        if (password_verify(
            $_POST['password'],
            require parent::root('resource', 'user', $email, 'password.php')
        )) {
            if ($_POST['csrf'] === salt_decrypt($_COOKIE['csrf'])) {
                setcookie('csrf', '', 0, '/');

                $this->email = $email;

                $this->nickname = $nickname;

                $this->token(parent::root('resource', 'user', $this->email, 'token'));
            } else {
                $this->int = 1;
            }
        }
    }

    private function cookie(array $nicknames): void
    {
        [$nickname, $token] = explode('.', salt_decrypt($_COOKIE['token']));

        if (
            isset($nicknames[$nickname])
        ) {
            $email = $nicknames[$nickname];

            if (
                is_file($filename = parent::root('resource', 'user', $email, 'token'))
                and
                $GLOBALS['_FW']->env['inaction'] > (time() - filemtime($filename))
                and
                $token === file_get_contents($filename)
            ) {
                $this->nickname = $nickname;

                $this->email = $email;

                $this->token($filename);
            }
        }
    }
    private function tabs(array $nicknames): void
    {
        if (
            isset($_COOKIE['csrf'])
        ) {
            $username = $_POST['username'];

            if (
                false == filter_var($username, FILTER_VALIDATE_EMAIL)
            ) {
                !isset($nicknames[$username]) || $this->post($nicknames[$username], $username);
            } else {
                $nicknames = array_flip($nicknames);

                !isset($nicknames[$username]) || $this->post($username, $nicknames[$username]);
            }
        } elseif (
            isset($_COOKIE['token'])
        ) {
            $this->cookie($nicknames);
        } else {
            $this->int = 1;
        }
    }

    public function __construct()
    {
        $nicknames = require parent::root('resource', 'user', 'nicknames.php');

        (
            'POST' === $_SERVER['REQUEST_METHOD']
            and
            isset($_POST['username'])
            and
            isset($_POST['password'])
        ) ?
            $this->tabs($nicknames)
            :
            !isset($_COOKIE['token']) || $this->cookie($nicknames);
    }
}
