<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch username and profile photo from session or database
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$profilePhoto = 'assets/img/pfp.png'; // Default

if (
    isset($_SESSION['profile_photo']) &&
    !empty($_SESSION['profile_photo']) &&
    file_exists($_SESSION['profile_photo'])
) {
    $profilePhoto = $_SESSION['profile_photo'];
} else {
    // Fetch from DB if not in session or file missing
    $stmt = $pdo->prepare("SELECT username, profile_photo FROM users WHERE id_user = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $username = $user['username'];
        if (!empty($user['profile_photo']) && file_exists($user['profile_photo'])) {
            $profilePhoto = $user['profile_photo'];
            $_SESSION['profile_photo'] = $profilePhoto;
        }
        $_SESSION['username'] = $username;
    }
}

// Automatically update expired tasks
$currentDate = date('Y-m-d');
$stmt = $pdo->prepare("UPDATE todos SET status = 'expired' WHERE deadline < ? AND status = 'pending' AND id_user = ?");
$stmt->execute([$currentDate, $_SESSION['user_id']]);

// Fetch all tasks for the logged-in user
$stmt = $pdo->prepare("SELECT * FROM todos WHERE id_user = ? ORDER BY date_created DESC");
$stmt->execute([$_SESSION['user_id']]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html data-theme="winter" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZenDo</title>
    <link rel="stylesheet" href="public/output.css">
    <link rel="shortcut icon" href="assets/favicon/favicon-32x32.png" type="image/x-icon">
     <link rel="stylesheet" href="css/all.css">
    <style>
        @keyframes fadeDown {
            0% {
                opacity: 0;
                transform: translateY(-30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-down {
            opacity: 0;
            animation: fadeDown 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
    </style>
</head>

<body class="relative">
    <div class="navbar bg-base-100 shadow-sm px-4 fixed top-0 z-10 w-full hidden md:flex">
        <div class="navbar-start">
            <div class="dropdown">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                </div>
                <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
        <div class="navbar-center">
            <a class="btn btn-ghost text-xl">ZenDo</a>
        </div>
        <div class="navbar-end gap-4">
            <div class="dropdown relative">
                <div tabindex="0" role="button" class="btn m-1">
                    Theme
                    <svg width="12px" height="12px" class="inline-block h-2 w-2 fill-current opacity-60"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2048 2048">
                        <path d="M1799 349l242 241-1017 1017L7 590l242-241 775 775 775-775z"></path>
                    </svg>
                </div>
                <ul tabindex="0"
                    class="dropdown-content absolute left-1/2 -translate-x-1/2 bg-base-300 rounded-box z-1 w-52 p-2 shadow-2xl">
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller w-full btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Winter (default)" value="winter" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller w-full btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Sunset" value="sunset" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller w-full btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Retro" value="retro" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller w-full btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Valentine" value="valentine" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller w-full btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Coffee" value="coffee" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller w-full btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Night" value="night" />
                    </li>
                    <li>
                        <input type="radio" name="theme-dropdown"
                            class="theme-controller w-full btn btn-sm btn-block btn-ghost justify-start"
                            aria-label="Dracula" value="dracula" />
                    </li>
                </ul>
            </div>

            <span class="text-sm font-medium"><?php echo htmlspecialchars($username); ?></span>
            <a href="edit_profile.php" class="avatar cursor-pointer">
                <div class="w-10 rounded-full">
                    <img src="<?php echo htmlspecialchars($profilePhoto) . '?v=' . time(); ?>" alt="Profile Photo">
                </div>
            </a>
        </div>
    </div>

    <div class="dock md:hidden">
        <button class="dock-active">
            <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <g fill="currentColor" stroke-linejoin="miter" stroke-linecap="butt">
                    <polyline points="1 11 12 2 23 11" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="2"></polyline>
                    <path d="m5,13v7c0,1.105.895,2,2,2h10c1.105,0,2-.895,2-2v-7" fill="none" stroke="currentColor" stroke-linecap="square" stroke-miterlimit="10" stroke-width="2"></path>
                    <line x1="12" y1="22" x2="12" y2="18" fill="none" stroke="currentColor" stroke-linecap="square" stroke-miterlimit="10" stroke-width="2"></line>
                </g>
            </svg>
            <span class="dock-label">Home</span>
        </button>
        <button>
            <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <g fill="currentColor" stroke-linejoin="miter" stroke-linecap="butt">
                    <circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-linecap="square" stroke-miterlimit="10" stroke-width="2"></circle>
                    <path d="m22,13.25v-2.5l-2.318-.966c-.167-.581-.395-1.135-.682-1.654l.954-2.318-1.768-1.768-2.318.954c-.518-.287-1.073-.515-1.654-.682l-.966-2.318h-2.5l-.966,2.318c-.581.167-1.135.395-1.654.682l-2.318-.954-1.768,1.768.954,2.318c-.287.518-.515,1.073-.682,1.654l-2.318.966v2.5l2.318.966c.167.581.395,1.135.682,1.654l-.954,2.318,1.768,1.768,2.318-.954c.518.287,1.073.515,1.654.682l.966,2.318h2.5l.966-2.318c.581-.167,1.135-.395,1.654-.682l2.318.954,1.768-1.768-.954-2.318c.287-.518.515-1.073.682-1.654l2.318-.966Z" fill="none" stroke="currentColor" stroke-linecap="square" stroke-miterlimit="10" stroke-width="2"></path>
                </g>
            </svg>
            <span class="dock-label">Settings</span>
        </button>
    </div>

    <section class="relative flex flex-col items-center w-screen h-dvh bg-base-300 md:pt-22 pt-11 px-4 gap-6">
        <div class="flex gap-2 w-full">
            <label class="input grow w-full">
                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                        stroke="currentColor">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </g>
                </svg>
                <input type="search" id="search" class="grow" placeholder="Search" oninput="filterTasks()" />
                <kbd class="kbd kbd-sm">Ctrl</kbd>
                <kbd class="kbd kbd-sm">K</kbd>
            </label>
            <button class="btn btn-primary" onclick="create_modal.showModal()">Create New</button>
            <dialog id="create_modal" class="modal">
                <div class="modal-box">
                    <form action="create_task.php" method="post">
                        <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="create_modal.close()">✕</button>
                        <h1 class="text-2xl font-medium mb-6">Create A New Task</h1>
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">What is your plan?</legend>
                            <input type="text" name="title" class="input w-full" placeholder="New Task" required />
                        </fieldset>
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Configure the deadline!</legend>
                            <input type="date" name="deadline" class="input w-full" required />
                        </fieldset>
                        <button type="submit" class="btn btn-primary mt-4 w-full">Create</button>
                    </form>
                </div>
            </dialog>
        </div>

        <div id="task-list" class="flex flex-col gap-4 w-full">
            <?php $i = 0;
            foreach ($tasks as $task): ?>
                <div class="task-item fade-down flex items-center justify-between p-4 bg-base-100 rounded-box shadow-sm"
                    data-title="<?php echo htmlspecialchars(strtolower($task['title'])); ?>"
                    style="animation-delay: <?php echo ($i * 0.08); ?>s;">
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <p class="text-lg font-medium"><?php echo htmlspecialchars($task['title']); ?></p>
                            <div class="badge badge-<?php
                                                    echo $task['status'] === 'completed' ? 'success' : ($task['status'] === 'expired' ? 'error' : 'warning'); ?> badge-sm">
                                <?php echo ucfirst($task['status']); ?>
                            </div>
                        </div>
                        <p class="text-sm font-normal"><i class="fa-solid fa-clock"></i> <?php echo htmlspecialchars($task['deadline']); ?></p>
                    </div>
                    <div class="flex gap-2">
                        <form action="update_status.php" method="post" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fa-solid fa-check"></i>
                            </button>
                        </form>
                        <button onclick="document.getElementById('edit_modal_<?php echo $task['id']; ?>').showModal()" class="btn btn-warning btn-sm">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <dialog id="edit_modal_<?php echo $task['id']; ?>" class="modal">
                            <div class="modal-box">
                                <form action="update_task.php" method="post">
                                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                    <h1 class="text-2xl font-medium mb-6">Edit Task</h1>
                                    <fieldset class="fieldset">
                                        <legend class="fieldset-legend">What is your plan?</legend>
                                        <input type="text" name="title" class="input w-full" value="<?php echo htmlspecialchars($task['title']); ?>" />
                                    </fieldset>
                                    <fieldset class="fieldset">
                                        <legend class="fieldset-legend">Configure the deadline!</legend>
                                        <input type="date" name="deadline" class="input w-full" value="<?php echo htmlspecialchars($task['deadline']); ?>" />
                                    </fieldset>
                                    <input type="hidden" name="id" value="<?php echo $task['id']; ?>" />
                                    <button type="submit" class="btn btn-warning mt-4 w-full">Update</button>
                                </form>
                            </div>
                        </dialog>
                        <button onclick="document.getElementById('delete_modal_<?php echo $task['id']; ?>').showModal()" class="btn btn-error btn-sm">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <dialog id="delete_modal_<?php echo $task['id']; ?>" class="modal">
                            <div class="modal-box">
                                <h3 class="text-lg font-bold">Delete Task?</h3>
                                <p class="py-4">Are you sure you want to delete this task?</p>
                                <div class="modal-action">
                                    <form action="delete_task.php" method="post">
                                        <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                                        <button type="submit" class="btn btn-error">Yes, Delete</button>
                                    </form>
                                    <button onclick="document.getElementById('delete_modal_<?php echo $task['id']; ?>').close()" class="btn">Cancel</button>
                                </div>
                            </div>
                        </dialog>
                    </div>
                </div>
            <?php $i++;
            endforeach; ?>
        </div>
    </section>

    <script>
        function filterTasks() {
            const searchInput = document.getElementById('search').value.toLowerCase();
            const tasks = document.querySelectorAll('.task-item');
            tasks.forEach(task => {
                const title = task.getAttribute('data-title');
                if (title.includes(searchInput)) {
                    task.style.display = 'flex';
                } else {
                    task.style.display = 'none';
                }
            });
        }
    </script>
    <script src="script.js"></script>
    <script type="module" src="https://unpkg.com/cally"></script>
</body>

</html>