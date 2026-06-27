<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
</head>
<body>
    <?php require BASE_PATH . '/src/Views/partials/navbar.php'; ?>
    <h1>Add Vinyl Record</h1>
    <a href="/products">Back to products</a>

    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="/products">
        <p>
            <label for="title">Title</label><br>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
        </p>
        <p>
            <label for="artist">Artist</label><br>
            <input type="text" id="artist" name="artist" value="<?= htmlspecialchars($_POST['artist'] ?? '') ?>" required>
        </p>
        <p>
            <label for="year">Year</label><br>
            <input type="number" id="year" name="year" value="<?= htmlspecialchars($_POST['year'] ?? '') ?>" min="1900" max="<?= date('Y') ?>" required>
        </p>
        <p>
            <label for="genre">Genre</label><br>
            <input type="text" id="genre" name="genre" value="<?= htmlspecialchars($_POST['genre'] ?? '') ?>" required>
        </p>
        <p>
            <label for="price">Price ($)</label><br>
            <input type="number" id="price" name="price" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" step="0.01" min="0.01" required>
        </p>
        <p>
            <label for="stock">Stock</label><br>
            <input type="number" id="stock" name="stock" value="<?= htmlspecialchars($_POST['stock'] ?? '0') ?>" min="0" required>
        </p>
        <p>
            <button type="submit">Create</button>
            <a href="/products">Cancel</a>
        </p>
    </form>
</body>
</html>
