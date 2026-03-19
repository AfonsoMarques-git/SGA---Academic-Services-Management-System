<?php
/**
 * Database Configuration with PDO
 * Secure connection using prepared statements
 */

$db_host = 'localhost';
$db_name = 'sagDatabase';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO(
        'mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8mb4',
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die('Erro de conexão: ' . $e->getMessage());
}

return $pdo;
