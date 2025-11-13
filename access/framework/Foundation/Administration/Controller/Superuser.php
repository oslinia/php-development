<?php

namespace Framework\Foundation\Administration\Controller;

use Framework\Foundation\Administration\Access\User;
use Framework\Foundation\Administration\View\Template;

class Superuser extends Template
{
    private function context(array $warning): array
    {
        foreach ($warning as $key => $value) {
            $warning[$key] = '<span class="text-danger">' . $value . '</span>' . PHP_EOL;
        }

        return ['warning' => $warning];
    }

    private function superuser(): object
    {
        return new class extends User {
            public bool $not_installed = false;

            private function write(string $filename): string
            {
                is_file($filename) || file_put_contents(
                    $filename,
                    '<?php return array();' . PHP_EOL,
                );

                return $filename;
            }

            private function mkdir(string $dirname): string
            {
                is_dir($dirname) || mkdir($dirname, recursive: true);

                return $dirname . DIRECTORY_SEPARATOR;
            }

            private function nicknames(): array
            {
                $dirname = $this->mkdir(parent::root('resource', 'user'));

                return require $this->write($dirname . 'nicknames.php');
            }

            public function __construct()
            {
                if (array() === $this->nicknames()) {
                    $this->not_installed = true;

                    parent::__construct();
                }
            }
        };
    }

    public function __invoke(): array
    {
        $user = $this->superuser();

        if ($user->not_installed) {
            return $user->bool ?
                parent::redirect_response(parent::url_for('panel'))
                :
                parent::render_template('access/registration.php', $this->context($user->warning));
        }

        return ['Not Found', 404, null, 'ASCII'];
    }
}
