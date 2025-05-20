<?php

include 'db_connection.php'; 

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use $pdo instead of $conn
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php?success=1");
            exit();
        } else {
            error_log("Failed login attempt for user: $username");
            header("Location: index.php?failed=1");
            exit();
        }
    } else {
        error_log("Username not found: $username");
        header("Location: index.php?failed=1");
        exit();
    }
}
?>