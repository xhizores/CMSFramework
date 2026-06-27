<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['title']) ?></title>
</head>
<body>
    <?php require BASE_PATH . '/src/Views/partials/navbar.php'; ?>
    <h1><?= htmlspecialchars($product['title']) ?></h1>
    <p><a href="/products">Back to products</a></p>

    <dl>
        <dt>Artist</dt>
        <dd><?= htmlspecialchars($product['artist']) ?></dd>

        <dt>Year</dt>
        <dd><?= htmlspecialchars((string) $product['year']) ?></dd>

        <dt>Genre</dt>
        <dd><?= htmlspecialchars($product['genre']) ?></dd>

        <dt>Price</dt>
        <dd>$<?= htmlspecialchars(number_format((float) $product['price'], 2)) ?></dd>

        <dt>Stock</dt>
        <dd><?= htmlspecialchars((string) $product['stock']) ?></dd>
    </dl>

    <a href="/products/<?= $product['id'] ?>/edit">Edit</a> |
    <form method="POST" action="/products/<?= $product['id'] ?>/delete" style="display:inline">
        <button type="submit" onclick="return confirm('Delete this product?')">Delete</button>
    </form>

    <hr>

    <h2>Owners</h2>

    <?php if (empty($owners)): ?>
        <p>No users own this vinyl yet.</p>
    <?php else: ?>
        <table border="1" cellpadding="6">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Qty</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($owners as $owner): ?>
                    <tr>
                        <td><?= htmlspecialchars($owner['name']) ?></td>
                        <td><?= htmlspecialchars($owner['email']) ?></td>
                        <td><?= htmlspecialchars((string) $owner['quantity']) ?></td>
                        <td>
                            <form method="POST" action="/products/<?= $product['id'] ?>/detach">
                                <input type="hidden" name="user_id" value="<?= $owner['id'] ?>">
                                <button type="submit">Remove copy</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if (!empty($allUsers)): ?>
        <h3>Add owner</h3>
        <form method="POST" action="/products/<?= $product['id'] ?>/attach">
            <select name="user_id">
                <?php foreach ($allUsers as $user): ?>
                    <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['email']) ?>)</option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Add copy</button>
        </form>
    <?php endif; ?>
</body>
</html>
