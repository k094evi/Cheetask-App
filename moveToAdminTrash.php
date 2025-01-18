<?php
include('Connect.php');

if (isset($_GET['id'])) {
    $taskId = intval($_GET['id']);

    // Fetch the task from the task table
    $query = "SELECT * FROM task WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $taskId);
    $stmt->execute();
    $result = $stmt->get_result();
    $task = $result->fetch_assoc();

    if ($task) {
        // Insert the task into the trash table
        $trashQuery = "INSERT INTO trash (title, description, priority, status, due_date, user_id, assigned_to) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $trashStmt = $conn->prepare($trashQuery);
        $trashStmt->bind_param(
            "sssssis",
            $task['title'],
            $task['description'],
            $task['priority'],
            $task['status'],
            $task['due_date'],
            $task['user_id'],
            $task['assigned_to']
        );        
        $trashStmt->execute();

        // Delete the task from the task table
        $deleteQuery = "DELETE FROM task WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("i", $taskId);
        $deleteStmt->execute();

        header("Location: AdminTask.php");
        exit();
    }
}

header("Location: AdminTask.php");
exit();
?>

