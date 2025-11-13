<?php

namespace Framework\Foundation\Administration;

use Framework\Foundation\Administration\Access\Auth;
use Framework\Foundation\Administration\View\Template;

class Access extends Template
{
    private Auth $auth;

    public function __construct()
    {
        $this->auth = new Auth;
    }

    public function access(): bool
    {
        return $this->auth->bool;
    }

    public function auth(): array
    {
        return parent::render_template('access/auth.php', [
            'warning' => isset($this->auth->int) ? '<p class="text-danger">' . [
                1 => 'There was an authorization cookie error.' .
                    ' You may have multiple browser tabs open.' .
                    ' Please try logging in again.',
            ][$this->auth->int] . '</p>' : '',
        ]);
    }
}
