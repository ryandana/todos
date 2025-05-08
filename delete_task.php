<?php
require 'db_connection.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM todos WHERE id = ?");
$stmt->execute([$id]);

header("Location: home.php");
exit;
