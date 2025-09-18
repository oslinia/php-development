<?php

use Framework\Frame\Path;

use function Framework\url_for;

/**
 * @var Path $path
 */

[$year, $month, $day] = explode(
    ' ',
    new \DateTime()->setTimezone(new \DateTimeZone('UTC'))->format('Y m d')
);

if (isset($path->day)) {
    $date = 'year: ' . $path->year . ' month: ' . $path->month . ' day: ' . $path->day;
    $url = url_for('page.archive', year: $year, month: $month, day: $day);
} elseif (isset($path->month)) {
    $date = 'year: ' . $path->year . ' month: ' . $path->month;
    $url = url_for('page.archive', year: $year, month: $month);
} else {
    $date = 'year: ' . $path->year;
    $url = url_for('page.archive', year: $year);
}

ob_start()

?>
<p class="header">Path: <?= $date ?></p>
<p class="header">Url : <?= $url ?></p>
<ul class="nav-list">
    <li><a href="<?= url_for('main') ?>">Homepage</a></li>
</ul>
<?php

$content = ob_get_clean();

require __DIR__ . '/../template/index.php';
