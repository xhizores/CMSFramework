<?php

declare(strict_types=1);

namespace CMS\Models;

use CMS\Core\Database;
use CMS\Core\Model;

class Product extends Model
{
    protected static string $table = 'products';

    public static function users(int $productId): array
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare(
            'SELECT u.*, up.quantity FROM users u
             INNER JOIN user_product up ON up.user_id = u.id
             WHERE up.product_id = ?
             ORDER BY u.name'
        );
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public static function attachUser(int $productId, int $userId): void
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare(
            'INSERT INTO user_product (user_id, product_id, quantity) VALUES (?, ?, 1)
             ON DUPLICATE KEY UPDATE quantity = quantity + 1'
        );
        $stmt->execute([$userId, $productId]);
    }

    public static function detachUser(int $productId, int $userId): void
    {
        $pdo = Database::getInstance();

        $pdo->prepare(
            'UPDATE user_product SET quantity = quantity - 1
             WHERE product_id = ? AND user_id = ?'
        )->execute([$productId, $userId]);

        $pdo->prepare(
            'DELETE FROM user_product WHERE product_id = ? AND user_id = ? AND quantity < 1'
        )->execute([$productId, $userId]);
    }
}
