<?php

declare(strict_types=1);

namespace CMS\Models;

use CMS\Core\Database;
use CMS\Core\Model;

class User extends Model
{
    protected static string $table = 'users';

    public static function findByEmail(string $email): ?array
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    public static function products(int $userId): array
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare(
            'SELECT p.*, up.quantity FROM products p
             INNER JOIN user_product up ON up.product_id = p.id
             WHERE up.user_id = ?
             ORDER BY p.title'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function allWithProducts(): array
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->query(
            'SELECT DISTINCT u.* FROM users u
             INNER JOIN user_product up ON up.user_id = u.id
             ORDER BY u.name'
        );
        $users = $stmt->fetchAll();

        foreach ($users as &$user) {
            $user['products'] = static::products((int) $user['id']);
        }

        return $users;
    }
}
