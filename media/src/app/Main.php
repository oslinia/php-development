<?php

namespace Application;

use Framework\Frame\Path;

use function Framework\{
    redirect_response,
    render_media,
    render_template,
    url_for,
};

class Main
{
    public function __invoke(): array
    {
        return render_template('main.php');
    }

    public function media(Path $path): array
    {
        return render_media($path->name);
    }

    public function query(): array
    {
        return render_template('navbar/query.php');
    }

    public function redirect(Path $path): array
    {
        $url = url_for('page', name: $path->name);

        return redirect_response($url);
    }
}
