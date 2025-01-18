<?php
session_start();
include('Connect.php');

// Check if user is logged in and is an admin
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
        $stmt = $conn->prepare("INSERT INTO task (title, due_date, status, priority, description, user_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $title, $dueDate, $taskStatus, $priority, $description, $developerId);
        if ($stmt->execute()) {
            // Redirect or display success message
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "All fields are required.";
    }
}

// Fetch developers for the dropdown
$developerResult = $conn->query("SELECT id, username FROM users WHERE user_type = 'developer'");
$developers = [];
if ($developerResult) {
    while ($row = $developerResult->fetch_assoc()) {
        $developers[] = $row;
    }
}

// Fetch all tasks for all users
$sql = "SELECT t.*, u.username AS assigned_to_name FROM task t LEFT JOIN users u ON t.assigned_to = u.id";
$taskResult = $conn->query($sql);

if (!$taskResult) {
    echo "Error: " . $conn->error;  // Output any SQL error
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheeTask: Task List</title>
    <link rel="stylesheet" href="AdminTask.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/f60434a0f6.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/png" href="LOGO.png">
</head>
<body>
    <header>
        <div class="header-left">
            <img src="LOGO.png" alt="logo" class="logo">
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
        </div>

        <div class="content">
            <h2>All Tasks</h2>
            <div class="table-container">
                <table class="task-table">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Description</th>
                            <th>Assigned To</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="taskContainer">
                    <?php
                    if ($taskResult && $taskResult->num_rows > 0) {
                        while ($row = $taskResult->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['assigned_to_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['priority']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['due_date']) . "</td>";
                            echo "<td>
                            <a href='moveToAdminTrash.php?id=" . $row["id"] . "'><i class='fas fa-trash trash-icon' style='color: black;'></i></a>
                            <a href='moveToAdminTrash.php?id=" . $row["id"] . "' style='margin-left: 20px;'><i class='fa-solid fa-check' style='color: black;'></i></a>
                            </td>";       
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No tasks available.</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="icon-container">
        <a href="AdminAdd.php"><i class="fas fa-plus add-icon"></i></a>
        <a href="AdminEdit.php"><i class="fas fa-edit edit-icon"></i></a>
    </div>

</body>
</html>
