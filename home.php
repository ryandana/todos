<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit;
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
require 'db_connection.php';

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
    <script src="https://kit.fontawesome.com/2278b63586.js" crossorigin="anonymous"></script>
</head>

<body class="relative">
    <div class="navbar bg-base-100 shadow-sm px-4 fixed top-0 z-10 w-full">
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
                    <img src="assets/img/pfp.png" />
                </div>
            </a>
        </div>
    </div>

    <section class="relative flex flex-col items-center w-screen h-dvh bg-base-300 pt-22 px-4 gap-6">
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
            <?php foreach ($tasks as $task): ?>
                <div class="task-item flex items-center justify-between p-4 bg-base-100 rounded-box shadow-sm" data-title="<?php echo htmlspecialchars(strtolower($task['title'])); ?>">
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
                                    <button class="btn" onclick="document.getElementById('delete_modal_<?php echo $task['id']; ?>').close()">Close</button>
                                    <form action="delete_task.php" method="get">
                                        <input type="hidden" name="id" value="<?php echo $task['id']; ?>" />
                                        <button type="submit" class="btn btn-error">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </dialog>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="join">
            <button class="join-item btn btn-active">1</button>
            <button class="join-item btn ">2</button>
            <button class="join-item btn">3</button>
            <button class="join-item btn">4</button>
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