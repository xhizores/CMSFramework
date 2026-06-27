<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($appName) ?></title>
</head>
<body>
    <?php require BASE_PATH . '/src/Views/partials/navbar.php'; ?>
    <h1>Welcome to <?= htmlspecialchars($appName) ?></h1>

    <h2>Collectors</h2>

    <?php if (empty($users)): ?>
        <p>No users own any vinyls yet.</p>
    <?php else: ?>
        <?php foreach ($users as $user): ?>
            <h3><a href="/users/<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?></a></h3>
            <table border="1" cellpadding="6">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Artist</th>
                        <th>Year</th>
                        <th>Qty</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($user['products'] as $product): ?>
                        <tr>
                            <td><a href="/products/<?= $product['id'] ?>"><?= htmlspecialchars($product['title']) ?></a></td>
                            <td><?= htmlspecialchars($product['artist']) ?></td>
                            <td><?= htmlspecialchars((string) $product['year']) ?></td>
                            <td><?= htmlspecialchars((string) $product['quantity']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
