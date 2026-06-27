<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit — <?= htmlspecialchars($product['title']) ?></title>
</head>
<body>
    <?php require BASE_PATH . '/src/Views/partials/navbar.php'; ?>
    <h1>Edit Vinyl Record</h1>
    <a href="/products/<?= $product['id'] ?>">Back to product</a>

    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="/products/<?= $product['id'] ?>/update">
        <p>
            <label for="title">Title</label><br>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($_POST['title'] ?? $product['title']) ?>" required>
        </p>
        <p>
            <label for="artist">Artist</label><br>
            <input type="text" id="artist" name="artist" value="<?= htmlspecialchars($_POST['artist'] ?? $product['artist']) ?>" required>
        </p>
        <p>
            <label for="year">Year</label><br>
            <input type="number" id="year" name="year" value="<?= htmlspecialchars($_POST['year'] ?? $product['year']) ?>" min="1900" max="<?= date('Y') ?>" required>
        </p>
        <p>
            <label for="genre">Genre</label><br>
            <input type="text" id="genre" name="genre" value="<?= htmlspecialchars($_POST['genre'] ?? $product['genre']) ?>" required>
        </p>
        <p>
            <label for="price">Price ($)</label><br>
            <input type="number" id="price" name="price" value="<?= htmlspecialchars($_POST['price'] ?? $product['price']) ?>" step="0.01" min="0.01" required>
        </p>
        <p>
            <label for="stock">Stock</label><br>
            <input type="number" id="stock" name="stock" value="<?= htmlspecialchars($_POST['stock'] ?? $product['stock']) ?>" min="0" required>
        </p>
        <p>
            <button type="submit">Save</button>
            <a href="/products/<?= $product['id'] ?>">Cancel</a>
        </p>
    </form>
</body>
</html>
