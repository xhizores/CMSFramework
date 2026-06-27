#!/usr/bin/env php
<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

use CMS\Core\Database;

// Load .env
$envFile = BASE_PATH . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value, " \t\n\r\0\x0B\"'");
    }
}

$pdo    = Database::getInstance();
$isStatus = in_array('--status', $argv ?? [], true);

// Ensure tracking table exists
$pdo->exec('CREATE TABLE IF NOT EXISTS migrations (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    filename   VARCHAR(255) NOT NULL UNIQUE,
    ran_at     TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
)');

// Load already-ran filenames
$ran = $pdo->query('SELECT filename, ran_at FROM migrations ORDER BY ran_at')
           ->fetchAll(PDO::FETCH_KEY_PAIR);

// Discover migration files
$files = glob(BASE_PATH . '/database/migrations/*.sql');
sort($files);

if (empty($files)) {
    echo "No migration files found in database/migrations/.\n";
    exit(0);
}

if ($isStatus) {
    echo str_pad('Status', 4) . ' ' . str_pad('File', 45) . " Ran at\n";
    echo str_repeat('-', 75) . "\n";
    foreach ($files as $path) {
        $name   = basename($path);
        $done   = isset($ran[$name]);
        $marker = $done ? '[✓]' : '[ ]';
        $date   = $done ? $ran[$name] : 'pending';
        echo $marker . ' ' . str_pad($name, 45) . " {$date}\n";
    }
    exit(0);
}

// Run pending migrations
$pending = array_filter($files, fn($path) => !isset($ran[basename($path)]));

if (empty($pending)) {
    echo "Nothing to migrate.\n";
    exit(0);
}

foreach ($pending as $path) {
    $name = basename($path);
    $sql  = file_get_contents($path);

    try {
        foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
            $pdo->exec($statement);
        }

        $stmt = $pdo->prepare('INSERT INTO migrations (filename) VALUES (?)');
        $stmt->execute([$name]);

        echo "[✓] {$name}\n";
    } catch (\PDOException $e) {
        echo "[✗] {$name} — " . $e->getMessage() . "\n";
        exit(1);
    }
}
