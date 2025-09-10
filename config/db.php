<?php
// Simple PDO connection using .env
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
  $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($lines as $line) {
    if (str_starts_with(trim($line), '#')) continue;
    [$k,$v] = array_map('trim', explode('=', $line, 2));
    $_ENV[$k] = $v;
  }
}
$DB_HOST = $_ENV['DB_HOST'] ?? '127.0.0.1';
$DB_NAME = $_ENV['DB_NAME'] ?? 'trello_native';
$DB_USER = $_ENV['DB_USER'] ?? 'root';
$DB_PASS = $_ENV['DB_PASS'] ?? '';

try {
  $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
} catch (Exception $e) {
  die("DB connection failed: " . $e->getMessage());
}
?>
