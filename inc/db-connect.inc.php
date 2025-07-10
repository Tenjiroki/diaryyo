<?php

require_once __DIR__ . '/../vendor/autoload.php';

$readenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$readenv->load();

try{
  $pdo = new PDO(
        'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'],
        [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  ]);
}
catch (PDOException $e){
  die('Database error: ' . $e->getMessage());
}

?>
