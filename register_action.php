<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $createdAt = date("Y-m-d H:i:s");

    // Cek apakah email atau username sudah digunakan
    $check = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $check->bind_param("ss", $email, $username);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "Email or Username already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (email, username, password, date_created) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $username, $hashedPassword, $createdAt);

        if ($stmt->execute()) {
            header("Location: register.php?success=1");
            
        } else {
            header("Location: register.php?failed=1");
            exit();
        }
    }
}
?>