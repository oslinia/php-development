<?php

namespace PHPFrame\Foundation\Application\Controller;

use PHPFrame\Foundation\Application\Access;

class Main extends Access
{
    public function __invoke(): array
    {
        if (parent::auth()) {
            return parent::login();
        }

        return parent::render_template('main.php');
    }
}
