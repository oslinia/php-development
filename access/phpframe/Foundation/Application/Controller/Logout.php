<?php

namespace PHPFrame\Foundation\Application\Controller;

use PHPFrame\Frame\Wrapper;

use function PHPFrame\url_for;

class Logout extends Wrapper
{
    public function __invoke(): array
    {
        setcookie('token', '', 0, '/');

        return parent::redirect_response(url_for('main'));
    }
}
