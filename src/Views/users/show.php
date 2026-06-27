<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User — <?= htmlspecialchars($user['name']) ?></title>
</head>
<body>
    <?php require BASE_PATH . '/src/Views/partials/navbar.php'; ?>
    <h1><?= htmlspecialchars($user['name']) ?></h1>

    <dl>
        <dt>ID</dt>
        <dd><?= htmlspecialchars((string) $user['id']) ?></dd>

        <dt>Email</dt>
        <dd><?= htmlspecialchars($user['email']) ?></dd>

        <dt>Created</dt>
        <dd><?= htmlspecialchars($user['created_at']) ?></dd>
    </dl>

    <a href="/users/<?= $user['id'] ?>/edit">Edit</a> |
    <form method="POST" action="/users/<?= $user['id'] ?>/delete" style="display:inline">
        <button type="submit" onclick="return confirm('Delete this user?')">Delete</button>
    </form>

    <br><br>
    <a href="/users">Back to users</a>

    <hr>

    <h2>Owned Vinyls</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if (empty($products)): ?>
        <p>This user owns no vinyls yet.</p>
    <?php else: ?>
        <table border="1" cellpadding="6">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Artist</th>
                    <th>Year</th>
                    <th>Genre</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><a href="/products/<?= $product['id'] ?>"><?= htmlspecialchars($product['title']) ?></a></td>
                        <td><?= htmlspecialchars($product['artist']) ?></td>
                        <td><?= htmlspecialchars((string) $product['year']) ?></td>
                        <td><?= htmlspecialchars($product['genre']) ?></td>
                        <td>$<?= htmlspecialchars(number_format((float) $product['price'], 2)) ?></td>
                        <td><?= htmlspecialchars((string) $product['quantity']) ?></td>
                        <td>
                            <form method="POST" action="/users/<?= $user['id'] ?>/detach">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <button type="submit">Remove copy</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if (!empty($allProducts)): ?>
        <h3>Add vinyl</h3>
        <form method="POST" action="/users/<?= $user['id'] ?>/attach">
            <select name="product_id">
                <?php foreach ($allProducts as $product): ?>
                    <option value="<?= $product['id'] ?>">
                        <?= htmlspecialchars($product['title']) ?> — <?= htmlspecialchars($product['artist']) ?>
                        <?php if ((int) $product['stock'] === 0): ?> (out of stock)<?php endif; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Add</button>
        </form>
    <?php endif; ?>
</body>
</html>
