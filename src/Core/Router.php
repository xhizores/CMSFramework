<?php

declare(strict_types=1);

namespace CMS\Core;

class Router
{
    private static array $routes = [];

    public static function get(string $uri, callable $handler): void
    {
        self::$routes[] = ['GET', $uri, $handler];
    }

    public static function post(string $uri, callable $handler): void
    {
        self::$routes[] = ['POST', $uri, $handler];
    }

    public static function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri    = rtrim($uri, '/') ?: '/';

        foreach (self::$routes as [$routeMethod, $routeUri, $handler]) {
            if ($routeMethod !== $method) {
                continue;
            }

            $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routeUri);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $handler(...$matches);
                return;
            }
        }

        http_response_code(404);
        echo '<h1>404 Not Found</h1>';
    }
}
