<?php
session_start();
include('Connect.php');

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: Login.php");
    exit();
}

$userId = $_SESSION['id'];
$userResult = $conn->query("SELECT * FROM users WHERE id = $userId");

if ($userResult) {
    $user = $userResult->fetch_assoc();
    
    // Restrict access to admin only
    if ($user['user_type'] !== 'admin') {
        // Redirect non-admin users to the login page or another page
        header("Location: Login.php");
        exit();
    }
}

// Handle Empty Trash request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['empty_trash'])) {
    $deleteQuery = "DELETE FROM trash";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->execute();
    $stmt->close();
    header("Location: AdminTrash.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheeTask: Trash</title>
    <link rel="stylesheet" href="AdminTrash.css">
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
            <a href="Home.html" class="logout-btn"><i class="fa-solid fa-right-from-bracket fa-2xl" style="color: #fff23e;"></i>Logout</a>
        </div>

        <section class="task-section">
            <h2>Trash</h2>
            <p><a href="AdminTask.php" class="return"> < BACK TO TASK LIST </a></p>

            <ul class="task-list">
            <?php
            // Modified query to fetch all tasks in trash and assigned user info
            $query = "SELECT t.*, u.username AS assigned_to FROM trash t LEFT JOIN users u ON t.assigned_to = u.id";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                echo "<li class='task-item'>";
                echo "<span class='task-details'>" . htmlspecialchars($row['title']) . " - " . htmlspecialchars($row['description']) . " (Assigned to: " . htmlspecialchars($row['assigned_to']) . ")</span>";
                echo "<div class='actions'>";
                echo "<a href='restoreAdminTask.php?id=" . $row['id'] . "'><i class='fas fa-undo restore-icon' style='color: black;'></i></a>";
                echo "<a href='deleteAdminPermanently.php?id=" . $row['id'] . "' style='margin-left: 20px;'><i class='fas fa-trash restore-permanent-icon' style='color: black;'></i></a>";
                echo "</div>";
                echo "</li>";
            }
            ?>
            </ul>
            <form method="POST">
                <button type="submit" name="empty_trash" class="empty-trash">Empty Trash</button>
            </form>
        </section>
    </div>

</body>
</html>
