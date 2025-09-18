<?php

use function Framework\url_for;

$dt = (new \DateTime)->setTimezone(new \DateTimeZone('UTC'));

[$year, $month, $day] = explode(' ', $dt->format('Y m d'));

ob_start()

?>
<p class="header">Main Page.</p>
<ul class="nav-list">
    <li><a href="<?= url_for('main.media', name: 'css/style.css') ?>" target="_blank">Media style.css</a></li>
    <li><a href="<?= url_for('main.query') . '?query=string' ?>">Query</a></li>
    <li><a href="<?= url_for('main.redirect', name: 'page') ?>">Redirect page</a></li>
    <li><a href="<?= url_for('page', name: 'name') ?>">Page name</a></li>
    <li><a href="<?= url_for('page.archive', year: $year) ?>">Archive year</a></li>
    <li><a href="<?= url_for('page.archive', year: $year, month: $month) ?>">Archive month</a></li>
    <li><a href="<?= url_for('page.archive', year: $year, month: $month, day: $day) ?>">Archive day</a></li>
</ul>
<?php

$content = ob_get_clean();

require __DIR__ . '/template/index.php';
