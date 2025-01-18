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
    
    // Check if the user is an admin
    if ($user['user_type'] != 'admin') {
        header("Location: Unauthorized.php");
        exit();
    }
}

// Fetch tasks with assigned developer name
$taskQuery = "SELECT t.*, u.username AS assigned_to_name FROM task t LEFT JOIN users u ON t.assigned_to = u.id";
$taskResult = $conn->query($taskQuery);

// Fetch all developers for the dropdown
$developerQuery = "SELECT id, username FROM users WHERE user_type = 'developer'";
$developerResult = $conn->query($developerQuery);
$developers = [];
while ($dev = $developerResult->fetch_assoc()) {
    $developers[] = $dev;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheeTask: Dashboard</title>
    <link rel="stylesheet" href="AdminEdit.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&family=Nunito:ital,wght@0;200..1000;1,200..1000&display=swap" rel="stylesheet">
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

        <section class="task-container">
            <div class="task-list">
                <h1>Edit Task</h1>
                <a href="AdminTask.php" class="return" id="back-to-task-list">&lt; BACK TO TASK LIST</a>
                <ul id="task-list">
                    <?php
                    if ($taskResult && $taskResult->num_rows > 0) {
                        while ($row = $taskResult->fetch_assoc()) {
                            echo "<li class='task-item' data-task-id='" . $row['id'] . "'>";
                            echo "<strong>Task:</strong> " . htmlspecialchars($row['title']) . "<br>";
                            echo "<strong>Assigned to:</strong> " . htmlspecialchars($row['assigned_to_name']) . "<br>";
                            echo "</li>";
                        }
                    } else {
                        echo "<li>No tasks available.</li>";
                    }
                    ?>
                </ul>
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
                        <label for="developer">Developer</label>
                        <select id="developer"> <!-- Changed to developer -->
    <option value="">Select</option>
    <?php
    $userQuery = "SELECT id, username FROM users WHERE user_type IN ('developer', 'admin')";
    $userResult = $conn->query($userQuery);
    while ($user = $userResult->fetch_assoc()) {
        echo "<option value='" . $user['id'] . "'>" . htmlspecialchars($user['username']) . "</option>";
    }
    ?>
</select>

                    </div>

                    <div class="form-group">
                        <label for="priority">Priority</label>
                        <select id="priority">
                            <option value="">Select</option>
                            <option>High</option>
                            <option>Medium</option>
                            <option>Low</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status">
                            <option value="">Select</option>
                            <option>Completed</option>
                            <option>In Progress</option>
                            <option>Not Started</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="due-date">Due Date</label>
                        <input type="date" id="due-date" required>
                    </div>

                    <button type="submit" class="save-button" style="font-family: 'Nunito', sans-serif;">Save Changes</button>
                </form>
            </div>
        </section>
    </div>

    <script src="AdminEdit.js"></script>
</body>
</html>
