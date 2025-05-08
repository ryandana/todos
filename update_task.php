<?php
require 'db_connection.php';

$id = $_POST['id'];
$title = $_POST['title'];
$deadline = $_POST['deadline'];

$stmt = $pdo->prepare("UPDATE todos SET title = ?, deadline = ? WHERE id = ?");
$stmt->execute([$title, $deadline, $id]);

header("Location: home.php");
exit;
