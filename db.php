<?php
declare(strict_types=1);

$host = '127.0.0.1';
$dbname = 'fisg_website';
$dbuser = 'root';
$dbpass = ''; // Default Laragon MySQL setup is often blank. Update if yours differs.

$dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $dbuser, $dbpass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    exit('Database connection failed. Check that MySQL is running in Laragon and import database.sql. Check db.php credentials if needed.');
}
