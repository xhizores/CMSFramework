<?php

declare(strict_types=1);

namespace CMS\Controllers;

use CMS\Core\Controller;
use CMS\Models\Product;
use CMS\Models\User;

class ProductController extends Controller
{
    public function index(): void
    {
        $products = Product::all();
        $this->render('products/index', ['products' => $products]);
    }

    public function show(string $id): void
    {
        $product = Product::find((int) $id);

        if ($product === null) {
            http_response_code(404);
            echo '<p>Product not found.</p>';
            return;
        }

        $this->render('products/show', [
            'product'  => $product,
            'owners'   => Product::users((int) $id),
            'allUsers' => User::all(),
        ]);
    }

    public function create(): void
    {
        $this->render('products/create', ['errors' => []]);
    }

    public function store(): void
    {
        $data   = $this->extractInput();
        $errors = $this->validate($data);

        if ($errors !== []) {
            $this->render('products/create', ['errors' => $errors]);
            return;
        }

        Product::create($data);
        $this->redirect('/products');
    }

    public function edit(string $id): void
    {
        $product = Product::find((int) $id);

        if ($product === null) {
            http_response_code(404);
            echo '<p>Product not found.</p>';
            return;
        }

        $this->render('products/edit', ['product' => $product, 'errors' => []]);
    }

    public function update(string $id): void
    {
        $product = Product::find((int) $id);

        if ($product === null) {
            http_response_code(404);
            echo '<p>Product not found.</p>';
            return;
        }

        $data   = $this->extractInput();
        $errors = $this->validate($data);

        if ($errors !== []) {
            $this->render('products/edit', ['product' => $product, 'errors' => $errors]);
            return;
        }

        Product::update((int) $id, $data);
        $this->redirect('/products/' . $id);
    }

    public function destroy(string $id): void
    {
        Product::delete((int) $id);
        $this->redirect('/products');
    }

    public function attach(string $id): void
    {
        $userId = (int) ($_POST['user_id'] ?? 0);

        if ($userId > 0) {
            Product::attachUser((int) $id, $userId);
        }

        $this->redirect('/products/' . $id);
    }

    public function detach(string $id): void
    {
        $userId = (int) ($_POST['user_id'] ?? 0);

        if ($userId > 0) {
            Product::detachUser((int) $id, $userId);
        }

        $this->redirect('/products/' . $id);
    }

    private function extractInput(): array
    {
        return [
            'title'  => trim($_POST['title']  ?? ''),
            'artist' => trim($_POST['artist'] ?? ''),
            'year'   => trim($_POST['year']   ?? ''),
            'genre'  => trim($_POST['genre']  ?? ''),
            'price'  => trim($_POST['price']  ?? ''),
            'stock'  => trim($_POST['stock']  ?? ''),
        ];
    }

    private function validate(array $data): array
    {
        $errors = [];

        if ($data['title'] === '') {
            $errors[] = 'Title is required.';
        }

        if ($data['artist'] === '') {
            $errors[] = 'Artist is required.';
        }

        $year = (int) $data['year'];
        if ($data['year'] === '' || $year < 1900 || $year > (int) date('Y')) {
            $errors[] = 'Year must be between 1900 and ' . date('Y') . '.';
        }

        if ($data['genre'] === '') {
            $errors[] = 'Genre is required.';
        }

        if ($data['price'] === '' || !is_numeric($data['price']) || (float) $data['price'] <= 0) {
            $errors[] = 'Price must be a positive number.';
        }

        if ($data['stock'] === '' || !ctype_digit($data['stock'])) {
            $errors[] = 'Stock must be a whole number of 0 or more.';
        }

        return $errors;
    }
}
