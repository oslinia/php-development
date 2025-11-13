<?php

namespace Framework\Foundation\Administration\Access;

use Framework\Frame;

class Validation extends Frame
{
    public array $warning = array();

    private function clear(string $name): void
    {
        $_POST[$name] = '';
    }

    private function warning(string $name, string $warning): void
    {
        $this->clear($name);

        $this->warning[$name] = $warning;
    }

    protected function valid_nickname(array $nicknames): void
    {
        if (false == preg_match('/^[A-Za-z0-9_]+$/', $_POST['nickname'])) {
            $this->warning(
                'nickname',
                'Valid characters: "A-Za-z0-9" and symbol "_".',
            );
        } elseif (3 > $l = strlen($_POST['nickname']) or $l > 32) {
            $this->warning(
                'nickname',
                'Nickname can be from 3 to 32 characters long.',
            );
        } elseif (isset($nicknames[$_POST['nickname']])) {
            $this->warning(
                'nickname',
                'Such nickname already exists.',
            );
        }
    }

    protected function valid_email(string $dirname): void
    {
        if (256 < strlen($_POST['email'])) {
            $this->warning(
                'email',
                'Email exceeds 256 characters.',
            );
        } elseif (false == filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $this->warning(
                'email',
                'Email address "' . $_POST['email'] . '" is incorrect.',
            );
        } elseif (is_dir($dirname . $_POST['email'])) {
            $this->warning(
                'email',
                'Such email address already exists.',
            );
        }
    }

    protected function valid_password(): void
    {
        if (4 > $l = strlen($_POST['password']) or $l > 32) {
            $this->clear('confirm');

            $this->warning(
                'password',
                'Password length must be between 4 and 32 characters.',
            );
        } elseif ($_POST['password'] !== $_POST['confirm']) {
            $this->clear('password');

            $this->warning(
                'confirm',
                'Password and confirmation do not match.',
            );
        }
    }
}
