<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Ensure the user is authenticated
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    // Check the current status and deadline of the task
    $stmt = $pdo->prepare("SELECT status, deadline FROM todos WHERE id = ? AND id_user = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($task) {
        $currentDate = date('Y-m-d'); // Get today's date

        // If the task is already completed, do nothing
        if ($task['status'] === 'completed') {
            header("Location: home.php");
            exit;
        }

        // If the deadline has passed, set the status to "expired"
        if ($task['deadline'] < $currentDate) {
            $status = 'expired';
        }

        // Update the task's status
        $updateStmt = $pdo->prepare("UPDATE todos SET status = ? WHERE id = ? AND id_user = ?");
        $updateStmt->execute([$status, $id, $_SESSION['user_id']]);
    }

    // Redirect back to the home page
    header("Location: home.php");
    exit;
}
?>