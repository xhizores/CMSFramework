<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User — <?= htmlspecialchars($user['name']) ?></title>
</head>
<body>
    <?php require BASE_PATH . '/src/Views/partials/navbar.php'; ?>
    <h1>Edit User</h1>

    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="/users/<?= $user['id'] ?>/update">
        <p>
            <label for="name">Name</label><br>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? $user['name']) ?>" required>
        </p>
        <p>
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? $user['email']) ?>" required>
        </p>
        <p>
            <button type="submit">Save</button>
            <a href="/users/<?= $user['id'] ?>">Cancel</a>
        </p>
    </form>
</body>
</html>
