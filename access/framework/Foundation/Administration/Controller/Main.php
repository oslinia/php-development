<?php

namespace Framework\Foundation\Administration\Controller;

use Framework\Foundation\Administration\Access;

class Main extends Access
{
    public function __invoke(): array
    {
        if (parent::access()) {
            return parent::auth();
        }

        return parent::render_template('main.php');
    }
}
