<?php return array (
  '/^\\/$/' => 
  array (
    0 => 'main',
    1 => 'Application\\Main',
  ),
  '/^\\/query$/' => 
  array (
    0 => 'main.query',
    1 => 'Application\\Main',
  ),
  '/^\\/redirect\\/([a-z]+).html$/' => 
  array (
    0 => 'main.redirect',
    1 => 'Application\\Main',
  ),
  '/^\\/page\\/([a-z]+).html$/' => 
  array (
    0 => 'page',
    1 => 'Application\\Page',
  ),
  '/^\\/archive\\/([0-9]{4})$/' => 
  array (
    0 => 'page.archive',
    1 => 'Application\\Page',
  ),
  '/^\\/archive\\/([0-9]{4})\\/([0-9]{1,2})$/' => 
  array (
    0 => 'page.archive',
    1 => 'Application\\Page',
  ),
  '/^\\/archive\\/([0-9]{4})\\/([0-9]{1,2})\\/([0-9]{1,2})$/' => 
  array (
    0 => 'page.archive',
    1 => 'Application\\Page',
  ),
);