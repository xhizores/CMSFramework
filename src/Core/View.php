<?php

declare(strict_types=1);

namespace CMS\Core;

class View
{
    public static function render(string $template, array $data = []): string
    {
        $path = BASE_PATH . '/src/Views/' . $template . '.php';

        if (!file_exists($path)) {
            throw new \RuntimeException("View not found: {$template}");
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $path;
        return (string) ob_get_clean();
    }
}
