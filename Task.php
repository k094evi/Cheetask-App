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
    <title>CheeTask: My Tasks</title>
    <link rel="stylesheet" href="Task.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
                <a href="Dashboard.php" class="sidebar-btn">Dashboard</a>
                <a href="Task.php" class="sidebar-btn">My Task</a>
                <a href="Trash.php" class="sidebar-btn">Trash</a>
            </div>
            <a href="Home.html" class="logout-btn"><i class="fa-solid fa-right-from-bracket fa-2xl" style="color: #fff23e;"></i>Logout</a>
        </div>


        <div class="content">
            <h2>My Tasks</h2>
            <div class="table-container">
                <table class="task-table">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Description</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="taskContainer">
<?php
$sql = "SELECT * FROM task WHERE user_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
        echo "<td>" . htmlspecialchars($row['priority']) . "</td>";
        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
        echo "<td>" . htmlspecialchars($row['due_date']) . "</td>";
        echo "<td>
        <a href='moveToTrash.php?id=" . $row["id"] . "'><i class='fas fa-trash trash-icon' style='color: black;'></i></a>
        <a href='moveToTrash.php?id=" . $row["id"] . "' style='margin-left: 20px;'><i class='fa-solid fa-check' style='color: black;'></i></a>
    </td>";       
        echo "</tr>";
    }

    $stmt->close();
}
?>
</tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="icon-container">
        <a href="Add.php"><i class="fas fa-plus add-icon"></i></a>
        <a href="Edit.php"><i class="fas fa-edit edit-icon"></i></a>
    </div>




</body>
</html>
