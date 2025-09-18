<?php

/**
 * @var string $content
 */

use function Framework\url_path;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Micro Application</title>
    <link href="<?= url_path('src/style.css') ?>" rel="stylesheet">
</head>

<body>
    <div class="container"><?= PHP_EOL . $content ?>
    </div>
</body>

</html>