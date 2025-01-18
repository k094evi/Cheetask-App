<?php
include('Connect.php');

if (isset($_GET['id'])) {
    $taskId = intval($_GET['id']);

    // Fetch the task from the trash table
    $query = "SELECT * FROM trash WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $taskId);
    $stmt->execute();
    $result = $stmt->get_result();
    $task = $result->fetch_assoc();

    if ($task) {
        // Insert the task back into the task table
        $restoreQuery = "INSERT INTO task (title, description, priority, status, due_date, user_id, assigned_to) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $restoreStmt = $conn->prepare($restoreQuery);
        $restoreStmt->bind_param(
            "sssssis",
            $task['title'],
            $task['description'],
            $task['priority'],
            $task['status'],
            $task['due_date'],
            $task['user_id'],
            $task['assigned_to']
        );
        $restoreStmt->execute();

        // Delete the task from the trash table
        $deleteQuery = "DELETE FROM trash WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("i", $taskId);
        $deleteStmt->execute();

        header("Location: Trash.php");
        exit();
    }
}

header("Location: Trash.php");
exit();
?>
