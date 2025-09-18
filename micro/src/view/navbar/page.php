<?php

use Framework\Frame\Path;

use function Framework\url_for;

/**
 * @var Path $path
 */

ob_start()

?>
<p class="header">Path: <?= $path->name ?></p>
<ul class="nav-list">
    <li><a href="<?= url_for('main') ?>">Homepage</a></li>
</ul>
<?php

$content = ob_get_clean();

require __DIR__ . '/../template/index.php';
