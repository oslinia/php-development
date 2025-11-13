<?php

namespace Framework\Foundation\Administration\Controller;

use Framework\Routing\Rule;

Rule::route('/panel', 'panel', Main::class);
Rule::route('/panel/logout', 'panel:logout', Logout::class);
Rule::route('/panel/superuser', 'panel:superuser', Superuser::class);
