<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create User</title>
</head>
<body>
    <?php require BASE_PATH . '/src/Views/partials/navbar.php'; ?>
    <h1>Create User</h1>

    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="/users">
        <p>
            <label for="name">Name</label><br>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
        </p>
        <p>
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </p>
        <p>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required minlength="8">
        </p>
        <p>
            <button type="submit">Create</button>
            <a href="/users">Cancel</a>
        </p>
    </form>
</body>
</html>
