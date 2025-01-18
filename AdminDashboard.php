<?php
session_start();
include('Connect.php');

if (!isset($_SESSION['id'])) {
    header("Location: Login.php");
    exit();
}

$userId = $_SESSION['id'];
$userResult = $conn->query("SELECT * FROM users WHERE id = $userId");

if ($userResult) {
    $user = $userResult->fetch_assoc();
}

// Modified query to fetch all tasks for every user along with the assigned user's username
$tasksResult = $conn->query("SELECT task.*, users.username AS assigned_username FROM task 
                             JOIN users ON task.assigned_to = users.id");

$tasks = [];
if ($tasksResult) {
    while ($task = $tasksResult->fetch_assoc()) {
        $tasks[] = $task;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheeTask: Dashboard</title>
    <link rel="stylesheet" href="AdminDashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/f60434a0f6.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/png" href="LOGO.png">
</head>
<body>
    <header>
        <div class="header-left">
            <img src="LOGO.png" alt="Logo" class="logo">
            <div class="name">
                <p>CheeTask</p>
            </div>
        </div>
        <div class="header-center">
            <div class="search-container">
                <input type="text" class="search" placeholder="Search your task here...">
            </div>
        </div>
        <div class="header-right">
        <p id="datetime"></p>
        <script src="Time.js"></script>
        </div>        
    </header>
    <div class="main-container">
        <div class="sidebar">
            <div class="user-info">
                <p class="user-name"><?php echo htmlspecialchars($user['username']); ?></p>
                <p class="user-email"><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
            <div class="sidebar-btns">
                <a href="AdminDashboard.php" class="sidebar-btn">Dashboard</a>
                <a href="AdminTask.php" class="sidebar-btn">My Task</a>
                <a href="AdminTrash.php" class="sidebar-btn">Trash</a>
            </div>
            <a href="Home.html" class="logout-btn"><i class="fa-solid fa-right-from-bracket fa-2xl" style="color: #fff23e;"></i>Logout</a>
        </div>
        <div class="content">
            <h2>Welcome back, User!</h2>
            <table class="task-table">
                <tr>
                    <th>Backlog</th>
                    <th>Working</th>
                    <th>Done</th>
                </tr>
                <tr>
                    <td><div id="backlogTasks"></div></td>
                    <td><div id="workingTasks"></div></td>
                    <td><div id="doneTasks"></div></td>
                </tr>
            </table>
        </div>
    </div>

    <script>
    const tasks = <?php echo json_encode($tasks); ?>;

    const backlogTasks = document.getElementById("backlogTasks");
    const workingTasks = document.getElementById("workingTasks");
    const doneTasks = document.getElementById("doneTasks");

    function renderTasks(filteredTasks = tasks) {
        backlogTasks.innerHTML = "";
        workingTasks.innerHTML = "";
        doneTasks.innerHTML = "";

        filteredTasks.forEach(task => {
            const taskElement = document.createElement("div");
            taskElement.classList.add("task-card");
            taskElement.innerHTML = `
                <h4><a href="#">${task.title}</a></h4>
                <p>${task.description}</p>
                <p>Due: ${task.due_date}</p>
                <p>Assigned to: ${task.assigned_username}</p> <!-- Displaying the assigned user's username -->
            `;

            if (task.status === "Not Started") {
                backlogTasks.appendChild(taskElement);
            } else if (task.status === "In Progress") {
                workingTasks.appendChild(taskElement);
            } else if (task.status === "Completed") {
                doneTasks.appendChild(taskElement);
            }
        });
    }

    const searchInput = document.querySelector('.search');
    searchInput.addEventListener('input', (event) => {
        const searchTerm = event.target.value.toLowerCase();
        const filteredTasks = tasks.filter(task =>
            task.title.toLowerCase().includes(searchTerm) ||
            task.description.toLowerCase().includes(searchTerm)
        );
        renderTasks(filteredTasks);
    });

    renderTasks();
</script>
</body>
</html>
