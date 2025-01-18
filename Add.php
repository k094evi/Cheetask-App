<?php
session_start();
include('Connect.php');

if (!isset($_SESSION['id'])) {
    header("Location: Login.php");
    exit();
}

$userId = $_SESSION['id'];
$result = $conn->query("SELECT * FROM users WHERE id = $userId");

if ($result) {
    $user = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheeTask: Create a Task</title>
    <link rel="stylesheet" href="Add.css">
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
                <a href="Dashboard.php" class="sidebar-btn">Dashboard</a>
                <a href="Task.php" class="sidebar-btn">My Task</a>
                <a href="Trash.php" class="sidebar-btn">Trash</a>
            </div>
            <a href="Home.html" class="logout-btn">
                <i class="fa-solid fa-right-from-bracket fa-2xl" style="color: #fff23e;"></i>Logout
            </a>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const logoutBtn = document.querySelector('.logout-btn');
                    logoutBtn.addEventListener('click', (event) => {
                        event.preventDefault();
                        const confirmLogout = confirm('Are you sure you want to log out?');
                        if (confirmLogout) {
                            window.location.href = 'Home.html';
                        }
                    });
                });
            </script>
        </div>








        <div class="task-form-container">
            <h1>Create Task</h1>
            <p><a href="Task.php" class="return"> < BACK TO TASK LIST </a></p>








            <form class="task-form">
                <div class="form-title">
                    <label for="title">Title</label>
                    <input type="text" id="title" placeholder="Add title" required>
                </div>








                <div class="form-group">
                    <div class="form-item">
                        <label for="due_date">Due date</label>
                        <input type="date" id="due_date" required>
                    </div>








                    <div class="form-item">
                        <label for="taskStatus">Task status</label>
                        <select id="taskStatus" required>
                            <option value="">Select</option>
                            <option value="Not Started" id="backlog">Not Started</option>
                            <option value="In Progress" id="working">In Progress</option>
                            <option value="Completed" id="done">Completed</option>
                        </select>
                    </div>








                    <div class="form-item">
                        <label for="priority">Priority</label>
                        <select id="priority" required>
                            <option value="">Select</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                </div>








                <div class="form-desc">
                    <label for="description" >Description</label>
                    <textarea id="description" placeholder="Add description" rows="5"></textarea>
                </div>
            </form>
            <button type="button" class="save-button">Save</button>
        </div>
    </div>
    <script src="Add.js"></script>
</body>
</html>
