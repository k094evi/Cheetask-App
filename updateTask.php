<?php
include('Connect.php');
session_start();

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$userId = $_SESSION['id'];
$taskId = $_GET['id'] ?? null;
$data = json_decode(file_get_contents('php://input'), true);

if (!$taskId || !isset($data['title'], $data['due_date'], $data['status'], $data['priority'], $data['description'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit();
}

$title = mysqli_real_escape_string($conn, $data['title']);
$due_date = mysqli_real_escape_string($conn, $data['due_date']);
$status = mysqli_real_escape_string($conn, $data['status']);
$priority = mysqli_real_escape_string($conn, $data['priority']);
$description = mysqli_real_escape_string($conn, $data['description']);

$sql = "UPDATE task SET title = ?, due_date = ?, status = ?, priority = ?, description = ? WHERE id = ? AND user_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("sssssii", $title, $due_date, $status, $priority, $description, $taskId, $userId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Task updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update task']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}

$conn->close();
?>
