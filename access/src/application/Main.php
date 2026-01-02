<?php

namespace Application;

use PHPFrame\Frame\View;

class Main extends View
{
    public function __invoke()
    {
        return parent::render_template('main.php');
    }
}
