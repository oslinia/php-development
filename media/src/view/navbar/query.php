<?php

use function Framework\{query_string, url_for};

ob_start()

?>
<p class="header">Query: <?= query_string() ?></p>
<ul class="nav-list">
    <li><a href="<?= url_for('main') ?>">Homepage</a></li>
</ul>
<?php

$content = ob_get_clean();

require __DIR__ . '/../template/index.php';
