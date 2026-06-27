<?php

declare(strict_types=1);

namespace CMS\Core;

use PDO;

abstract class Model
{
    protected static string $table;

    public static function all(): array
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->query('SELECT * FROM ' . static::$table . ' ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare('SELECT * FROM ' . static::$table . ' WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    public static function create(array $data): int
    {
        $pdo     = Database::getInstance();
        $columns = implode(', ', array_keys($data));
        $holders = implode(', ', array_fill(0, count($data), '?'));
        $stmt    = $pdo->prepare('INSERT INTO ' . static::$table . " ({$columns}) VALUES ({$holders})");
        $stmt->execute(array_values($data));
        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo        = Database::getInstance();
        $assignments = implode(', ', array_map(fn($col) => "{$col} = ?", array_keys($data)));
        $stmt        = $pdo->prepare('UPDATE ' . static::$table . " SET {$assignments} WHERE id = ?");
        $stmt->execute([...array_values($data), $id]);
    }

    public static function delete(int $id): void
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare('DELETE FROM ' . static::$table . ' WHERE id = ?');
        $stmt->execute([$id]);
    }
}
