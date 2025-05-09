<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $createdAt = date("Y-m-d H:i:s");

    // Check if email or username already exists
    $check = $pdo->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $check->execute([$email, $username]);
    $result = $check->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo "Email or Username already exists!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (email, username, password, date_created) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$email, $username, $hashedPassword, $createdAt])) {
            header("Location: register.php?success=1");
            exit();
        } else {
            header("Location: register.php?failed=1");
            exit();
        }
    }
}
