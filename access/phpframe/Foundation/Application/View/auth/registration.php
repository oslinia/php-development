<?php

use function PHPFrame\csrf_token;

/**
 * @var array $warning
 */

$title = 'Registration form';

ob_start();

?>
<div class="container">
    <form method="post">
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
        <div class="mb-3">
            <label for="nickname" class="form-label">Nickname</label>
            <input type="text" class="form-control" id="nickname" name="nickname"
                value="<?= $_POST['nickname'] ?? '' ?>"><?= $warning['nickname'] ?? PHP_EOL ?>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="text" class="form-control" id="email" name="email"
                value="<?= $_POST['email'] ?? '' ?>"><?= $warning['email'] ?? PHP_EOL ?>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password"
                value="<?= $_POST['password'] ?? '' ?>"><?= $warning['password'] ?? PHP_EOL ?>
        </div>
        <div class="mb-3">
            <label for="confirm" class="form-label">Confirm password</label>
            <input type="password" class="form-control" id="confirm" name="confirm"
                value="<?= $_POST['confirm'] ?? '' ?>"><?= $warning['confirm'] ?? PHP_EOL ?>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<?php

$body = ob_get_clean();

require dirname(__DIR__) . '/template/auth.php';
