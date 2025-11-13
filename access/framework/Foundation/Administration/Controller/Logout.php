<?php

namespace Framework\Foundation\Administration\Controller;

use Framework\Frame\Wrapper;

class Logout extends Wrapper
{
    public function __invoke(): array
    {
        setcookie('token', '', 0, '/');

        return parent::redirect_response(parent::url_for('main'));
    }
}
