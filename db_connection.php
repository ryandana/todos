<?php
$servername = "localhost";
$db_username = "root"; // Renamed to avoid conflict
$password = "";
$database = "db_todolist";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $db_username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>