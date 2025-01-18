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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheeTask: Edit Task</title>
    <link rel="stylesheet" href="Edit.css">
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
        </div>
        <section class="task-container">
            <div class="task-list">
                <h1>Edit Task</h1>
                <a href="Task.php" class="return" id="back-to-task-list">&lt; BACK TO TASK LIST</a>
                <ul id="task-list"></ul>
            </div>
            <div class="task-editor">
                <h2>Edit Task</h2>
                <form id="task-form">
                    <div class="form-group">
                        <label for="task-title">Task Title</label>
                        <input type="text" id="task-title" placeholder="Title" required>
                    </div>
                    <div class="form-group">
                        <label for="task-desc">Task Description</label>
                        <textarea id="task-desc" placeholder="Description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="priority">Priority</label>
                        <select id="priority">
                            <option>High</option>
                            <option>Medium</option>
                            <option>Low</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status">
                            <option>Completed</option>
                            <option>In Progress</option>
                            <option>Not Started</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="due-date">Due Date</label>
                        <input type="date" id="due-date" required>
                    </div>
                    <button type="submit" class="save-button">Save Changes</button>
                </form>
            </div>
        </section>
    </div>
    <script src="Edit.js"></script>
</body>
</html>
