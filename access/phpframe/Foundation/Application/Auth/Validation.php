<?php

namespace PHPFrame\Foundation\Application\Auth;

class Validation
{
    private array $warning;

    private function add(string $name, string $warning): void
    {
        $_POST[$name] = '';

        $this->warning[$name] = $warning;
    }

    private function valid_nickname(array $nicknames): void
    {
        if (false == preg_match('/^[A-Za-z0-9_]+$/', $_POST['nickname'])) {
            $this->add(
                'nickname',
                'Valid characters: "A-Za-z0-9" and symbol "_".'
            );
        } elseif (3 > $l = strlen($_POST['nickname']) or $l > 32) {
            $this->add(
                'nickname',
                'Nickname can be from 3 to 32 characters long.'
            );
        } elseif (isset($nicknames[$_POST['nickname']])) {
            $this->add(
                'nickname',
                'Such nickname already exists.'
            );
        }
    }

    private function valid_email(string $dirname): void
    {
        if (256 < strlen($_POST['email'])) {
            $this->add(
                'email',
                'Email exceeds 256 characters.'
            );
        } elseif (false == filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $this->add(
                'email',
                'Email address "' . $_POST['email'] . '" is incorrect.'
            );
        } elseif (is_dir($dirname . DIRECTORY_SEPARATOR . $_POST['email'])) {
            $this->add(
                'email',
                'Such email address already exists.'
            );
        }
    }

    private function valid_password(): void
    {
        if (4 > $l = strlen($_POST['password']) or $l > 32) {
            $this->add(
                'password',
                'Password length must be between 4 and 32 characters.'
            );

            $_POST['confirm'] = '';
        } elseif ($_POST['password'] !== $_POST['confirm']) {
            $_POST['password'] = '';

            $this->add(
                'confirm',
                'Password and confirmation do not match.'
            );
        }
    }

    public function __construct(array &$warning, array $nicknames, string $dirname)
    {
        $this->warning = &$warning;

        $this->valid_nickname($nicknames);
        $this->valid_email($dirname);
        $this->valid_password();
    }
}
