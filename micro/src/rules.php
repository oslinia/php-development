<?php

namespace Application;

use PHPFrame\Routing\Rule;

Rule::route('/', 'main', Main::class);

Rule::route('/redirect/{name}.html', 'main.redirect', Main::class)
    ->where(name: '[a-z]+');

Rule::route('/page/{name}.html', 'page', Page::class)
    ->where(name: '[a-z]+');

Rule::route('/archive/{year}', 'page.archive', Page::class)
    ->where(year: '[0-9]{4}');
Rule::route('/archive/{year}/{month}', 'page.archive', Page::class)
    ->where(year: '[0-9]{4}', month: '[0-9]{1,2}');
Rule::route('/archive/{year}/{month}/{day}', 'page.archive', Page::class)
    ->where(year: '[0-9]{4}', month: '[0-9]{1,2}', day: '[0-9]{1,2}');
