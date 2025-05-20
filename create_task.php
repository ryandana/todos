<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['user_id'];
$title = $_POST['title'];
$deadline = $_POST['deadline'];

$stmt = $pdo->prepare("INSERT INTO todos (id_user, title, deadline) VALUES (?, ?, ?)");
$stmt->execute([$id_user, $title, $deadline]);

header("Location: home.php");
exit;
?>