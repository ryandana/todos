<?php
require 'db_connection.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("UPDATE todos SET status = 'completed' WHERE id = ?");
$stmt->execute([$id]);

header("Location: home.php");
exit;
