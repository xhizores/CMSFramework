<?php

declare(strict_types=1);

namespace CMS\Core;

abstract class Controller
{
    protected function render(string $view, array $data = []): void
    {
        echo View::render($view, $data);
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}
