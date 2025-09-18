<?php return array (
  'main' => 
  array (
    0 => 
    array (
      0 => '/',
      1 => '/^\\/$/',
    ),
  ),
  'main.media' => 
  array (
    1 => 
    array (
      0 => '/media/{name}',
      1 => '/^\\/media\\/([a-z\\/\\.]+)$/',
    ),
  ),
  'main.query' => 
  array (
    0 => 
    array (
      0 => '/query',
      1 => '/^\\/query$/',
    ),
  ),
  'main.redirect' => 
  array (
    1 => 
    array (
      0 => '/redirect/{name}.html',
      1 => '/^\\/redirect\\/([a-z]+).html$/',
    ),
  ),
  'page' => 
  array (
    1 => 
    array (
      0 => '/page/{name}.html',
      1 => '/^\\/page\\/([a-z]+).html$/',
    ),
  ),
  'page.archive' => 
  array (
    1 => 
    array (
      0 => '/archive/{year}',
      1 => '/^\\/archive\\/([0-9]{4})$/',
    ),
    2 => 
    array (
      0 => '/archive/{year}/{month}',
      1 => '/^\\/archive\\/([0-9]{4})\\/([0-9]{1,2})$/',
    ),
    3 => 
    array (
      0 => '/archive/{year}/{month}/{day}',
      1 => '/^\\/archive\\/([0-9]{4})\\/([0-9]{1,2})\\/([0-9]{1,2})$/',
    ),
  ),
);