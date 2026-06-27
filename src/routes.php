<?php

declare(strict_types=1);

use CMS\Core\Router;
use CMS\Core\View;
use CMS\Controllers\UserController;
use CMS\Controllers\ProductController;

Router::get('/', function () {
    echo View::render('home', [
        'appName' => $_ENV['APP_NAME'] ?? 'CMSFramework',
        'users'   => \CMS\Models\User::allWithProducts(),
    ]);
});

Router::get('/users',              [new UserController(), 'index']);
Router::get('/users/create',       [new UserController(), 'create']);
Router::post('/users',             [new UserController(), 'store']);
Router::get('/users/{id}',         [new UserController(), 'show']);
Router::get('/users/{id}/edit',    [new UserController(), 'edit']);
Router::post('/users/{id}/update', [new UserController(), 'update']);
Router::post('/users/{id}/delete', [new UserController(), 'destroy']);
Router::post('/users/{id}/attach', [new UserController(), 'attach']);
Router::post('/users/{id}/detach', [new UserController(), 'detach']);

Router::get('/products',                  [new ProductController(), 'index']);
Router::get('/products/create',           [new ProductController(), 'create']);
Router::post('/products',                 [new ProductController(), 'store']);
Router::get('/products/{id}',             [new ProductController(), 'show']);
Router::get('/products/{id}/edit',        [new ProductController(), 'edit']);
Router::post('/products/{id}/update',     [new ProductController(), 'update']);
Router::post('/products/{id}/delete',     [new ProductController(), 'destroy']);
Router::post('/products/{id}/attach',     [new ProductController(), 'attach']);
Router::post('/products/{id}/detach',     [new ProductController(), 'detach']);
