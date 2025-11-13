<?php

use function Framework\url_path;

/**
 * @var string $title
 * @var string $body
 */

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="<?= url_path('bootstrap/5.3.8.css') ?>">
    <link rel="stylesheet" href="<?= url_path('panel/access.css') ?>">
</head>
<body><?= PHP_EOL . $body ?>
</body>
</html>