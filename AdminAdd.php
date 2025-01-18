<?php
session_start();
include('Connect.php');

if (!isset($_SESSION['id'])) {
    header("Location: Login.php");
    exit();
}

$userId = $_SESSION['id'];
$userResult = $conn->query("SELECT * FROM users WHERE id = $userId");

if ($userResult && $userResult->num_rows > 0) {
    $user = $userResult->fetch_assoc();
    if ($user['user_type'] !== 'admin') {
        echo "Access denied. Only admins can access this page.";
        exit();
    }
} else {
    echo "User not found.";
    exit();
}

// Handle task creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $dueDate = $_POST['dueDate'];
    $taskStatus = $_POST['taskStatus'];
    $priority = $_POST['priority'];
    $description = trim($_POST['description']);
    $developerId = $_POST['developer'];

    if ($title && $dueDate && $taskStatus && $priority && $developerId) {
        // Insert with both assigned_to and user_id field
        $stmt = $conn->prepare("INSERT INTO task (title, due_date, status, priority, description, assigned_to, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $title, $dueDate, $taskStatus, $priority, $description, $developerId, $developerId);
        if ($stmt->execute()) {
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "All fields are required.";
    }    
}

// Fetch all users (no filter for developers)
$userResult = $conn->query("SELECT id, username FROM users");
$users = [];
if ($userResult) {
    while ($row = $userResult->fetch_assoc()) {
        $users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheeTask: Create a Task</title>
    <link rel="stylesheet" href="AdminAdd.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
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
            <p><a href="AdminTask.php" class="return"> < BACK TO TASK LIST </a></p>
            <form method="POST" class="task-form">
                <div class="form-title">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" placeholder="Add title" required>
                </div>
                <div class="form-group">
                    <div class="form-item">
                        <label for="dueDate">Due date</label>
                        <input type="date" id="dueDate" name="dueDate" required>
                    </div>
                    <div class="form-item">
                        <label for="taskStatus">Task status</label>
                        <select id="taskStatus" name="taskStatus" required>
                            <option value="">Select</option>
                            <option value="Not Started">Not Started</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                    <div class="form-item">
                        <label for="priority">Priority</label>
                        <select id="priority" name="priority" required>
                            <option value="">Select</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                </div>
                <div class="form-desc">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Add description" rows="5"></textarea>
                </div>
                <div class="form-dev">
                    <label for="developer">Assign To</label>
                    <select id="developer" name="developer" required>
                        <option value="">Select</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?php echo $user['id']; ?>">
                                <?php echo htmlspecialchars($user['username']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="save-button">Save</button>
            </form>
        </div>
    </div>
</body>
</html>
