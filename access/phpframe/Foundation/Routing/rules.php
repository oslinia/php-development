<?php

namespace PHPFrame\Foundation\Application\Controller;

use PHPFrame\Routing\Rule;

Rule::route('/panel', 'panel', Main::class);
Rule::route('/panel/logout', 'panel:logout', Logout::class);
