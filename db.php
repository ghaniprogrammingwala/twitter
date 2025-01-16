<?php
$host = 'localhost';
$db = 'dbondkb627yd6k';
$user = 'u7ghphl6wbgc1';
$password = '4j0z0gkuuwj3';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
