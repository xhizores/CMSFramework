<?php

declare(strict_types=1);

namespace CMS\Controllers;

use CMS\Core\Controller;
use CMS\Models\Product;
use CMS\Models\User;

class UserController extends Controller
{
    public function index(): void
    {
        $users = User::all();
        $this->render('users/index', ['users' => $users]);
    }

    public function show(string $id, string $error = ''): void
    {
        $user = User::find((int) $id);

        if ($user === null) {
            http_response_code(404);
            echo '<p>User not found.</p>';
            return;
        }

        $ownedProducts = User::products((int) $id);

        $this->render('users/show', [
            'user'        => $user,
            'products'    => $ownedProducts,
            'allProducts' => Product::all(),
            'error'       => $error,
        ]);
    }

    public function attach(string $id): void
    {
        $productId = (int) ($_POST['product_id'] ?? 0);
        $product   = $productId > 0 ? Product::find($productId) : null;

        if ($product === null) {
            $this->show($id, 'Please select a valid product.');
            return;
        }

        if ((int) $product['stock'] === 0) {
            $this->show($id, $product['title'] . " is out of stock and cannot be added.");
            return;
        }

        Product::attachUser($productId, (int) $id);
        $this->redirect('/users/' . $id);
    }

    public function detach(string $id): void
    {
        $productId = (int) ($_POST['product_id'] ?? 0);

        if ($productId > 0) {
            Product::detachUser($productId, (int) $id);
        }

        $this->redirect('/users/' . $id);
    }

    public function create(): void
    {
        $this->render('users/create', ['errors' => []]);
    }

    public function store(): void
    {
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $errors = [];

        if ($name === '') {
            $errors[] = 'Name is required.';
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email address is required.';
        } elseif (User::findByEmail($email) !== null) {
            $errors[] = 'That email address is already taken.';
        }

        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }

        if ($errors !== []) {
            $this->render('users/create', ['errors' => $errors]);
            return;
        }

        User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
        ]);

        $this->redirect('/users');
    }

    public function edit(string $id): void
    {
        $user = User::find((int) $id);

        if ($user === null) {
            http_response_code(404);
            echo '<p>User not found.</p>';
            return;
        }

        $this->render('users/edit', ['user' => $user, 'errors' => []]);
    }

    public function update(string $id): void
    {
        $user = User::find((int) $id);

        if ($user === null) {
            http_response_code(404);
            echo '<p>User not found.</p>';
            return;
        }

        $name  = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        $errors = [];

        if ($name === '') {
            $errors[] = 'Name is required.';
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email address is required.';
        } else {
            $existing = User::findByEmail($email);
            if ($existing !== null && (int) $existing['id'] !== (int) $id) {
                $errors[] = 'That email address is already taken.';
            }
        }

        if ($errors !== []) {
            $this->render('users/edit', ['user' => $user, 'errors' => $errors]);
            return;
        }

        User::update((int) $id, ['name' => $name, 'email' => $email]);

        $this->redirect('/users/' . $id);
    }

    public function destroy(string $id): void
    {
        User::delete((int) $id);
        $this->redirect('/users');
    }
}
