<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
</head>
<body>
    <?php require BASE_PATH . '/src/Views/partials/navbar.php'; ?>
    <h1>Vinyl Records</h1>
    <a href="/products/create">Add new product</a>

    <?php if (empty($products)): ?>
        <p>No products found.</p>
    <?php else: ?>
        <table border="1" cellpadding="6">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Artist</th>
                    <th>Year</th>
                    <th>Genre</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) $product['id']) ?></td>
                        <td><?= htmlspecialchars($product['title']) ?></td>
                        <td><?= htmlspecialchars($product['artist']) ?></td>
                        <td><?= htmlspecialchars((string) $product['year']) ?></td>
                        <td><?= htmlspecialchars($product['genre']) ?></td>
                        <td>$<?= htmlspecialchars(number_format((float) $product['price'], 2)) ?></td>
                        <td><?= htmlspecialchars((string) $product['stock']) ?></td>
                        <td>
                            <a href="/products/<?= $product['id'] ?>">View</a> |
                            <a href="/products/<?= $product['id'] ?>/edit">Edit</a> |
                            <form method="POST" action="/products/<?= $product['id'] ?>/delete" style="display:inline">
                                <button type="submit" onclick="return confirm('Delete this product?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
