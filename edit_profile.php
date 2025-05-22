<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    if (isset($_POST['username']) && !empty($_POST['username'])) {
        $username = htmlspecialchars($_POST['username']);
        $_SESSION['username'] = $username;

        // Update the username in the database
        $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE id_user = ?");
        $stmt->execute([$username, $_SESSION['user_id']]);
    }

    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $userId = $_SESSION['user_id'];
        $fileExtension = strtolower(pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedExtensions)) {
            $uploadFile = $uploadDir . 'profile_' . $userId . '.' . $fileExtension;

            // Ensure the uploads directory exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Delete old profile photo if not default
            $stmt = $pdo->prepare("SELECT profile_photo FROM users WHERE id_user = ?");
            $stmt->execute([$userId]);
            $oldPhoto = $stmt->fetchColumn();
            if ($oldPhoto && $oldPhoto !== 'assets/img/pfp.png' && file_exists($oldPhoto)) {
                unlink($oldPhoto);
            }

            // Move uploaded file to the uploads directory
            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $uploadFile)) {
                $_SESSION['profile_photo'] = $uploadFile;

                // Update the database with the new profile photo path
                $stmt = $pdo->prepare("UPDATE users SET profile_photo = ? WHERE id_user = ?");
                $stmt->execute([$uploadFile, $userId]);
            }
        }
    }

    // Redirect to home page after saving changes
    header("Location: home.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="winter">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="public/output.css">
    <link rel="shortcut icon" href="assets/favicon/favicon-32x32.png" type="image/x-icon">
    <link rel="stylesheet" href="css/all.css">
</head>

<body class="bg-base-300 min-h-screen flex items-center justify-center">

    <div class="card w-full max-w-md bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title text-center">Edit Profile</h2>
            <form method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                <!-- Profile Photo -->
                <div class="form-control flex flex-col items-center">
                    <div class="avatar flex justify-center">
                        <div class="w-24 rounded-full">
                            <img id="profilePreview" src="<?php
                                                            $photo = isset($_SESSION['profile_photo']) && file_exists($_SESSION['profile_photo']) ? $_SESSION['profile_photo'] : 'assets/img/pfp.png';
                                                            echo $photo . '?v=' . time();
                                                            ?>" alt="Profile Photo">
                        </div>
                    </div>
                    <input type="file" name="profile_photo" class="file-input file-input-bordered w-1/4 mt-2" onchange="previewProfilePhoto(event)" />
                </div>

                <!-- Username -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Username</span>
                    </label>
                    <input type="text" name="username" placeholder="Enter your username" class="input input-bordered w-full" required minlength="3" maxlength="30" pattern="[A-Za-z][A-Za-z0-9\-]*" title="Only letters, numbers, or dashes are allowed" value="<?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''; ?>" />
                </div>

                <!-- Save Changes Button -->
                <div class="form-control mt-4">
                    <button type="submit" class="btn btn-primary w-full">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
    <script>
        function previewProfilePhoto(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('profilePreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>

</html>