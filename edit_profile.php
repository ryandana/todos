<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="public/output.css">
    <link rel="shortcut icon" href="assets/favicon/favicon-32x32.png" type="image/x-icon">
    <script src="https://kit.fontawesome.com/2278b63586.js" crossorigin="anonymous"></script>
</head>

<body>

    <section class="relative flex flex-col justify-center items-center w-screen h-dvh bg-base-300 gap-6">
        <div class="flex flex-col items-center gap-6">
            <div class="flex justify-between items-center gap-6">
                <div class="avatar">
                    <div class="w-48 rounded-full">
                        <img src="https://img.daisyui.com/images/profile/demo/yellingcat@192.webp" />
                    </div>
                </div>
                <input type="file" class="file-input" />
            </div>
            <label class="input validator w-full">
                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none" stroke="currentColor">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </g>
                </svg>
                <input type="input" required placeholder="Username" name="username" pattern="[A-Za-z][A-Za-z0-9\-]*" minlength="3" maxlength="30" title="Only letters, numbers or dash" />
            </label>
            <p class="validator-hint hidden">
                Must be 3 to 30 characters
                <br />containing only letters, numbers or dash
            </p>
        </div>
    </section>

    <script src="script.js"></script>
    <script type="module" src="https://unpkg.com/cally"></script>
</body>

</html>