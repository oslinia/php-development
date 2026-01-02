<?php

use PHPFrame\Frame\Path;

use function PHPFrame\url_for;

/**
 * @var Path $path
 */

ob_start()

?>
<div class="container">
    <p>Path: <?= $path->name ?></p>
    <ul class="nav-list">
        <li><a href="<?= url_for('main') ?>">Home</a></li>
    </ul>
</div>
<?php

$body = ob_get_clean();

require __DIR__ . '/..' . '/template/index.php';
