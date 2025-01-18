<?php
include('Connect.php');
session_start();

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$userId = $_SESSION['id'];
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['title'], $data['due_date'], $data['status'], $data['priority'], $data['description'])) {
    $title = mysqli_real_escape_string($conn, $data['title']);
    $due_date = mysqli_real_escape_string($conn, $data['due_date']);
    $status = mysqli_real_escape_string($conn, $data['status']);
    $priority = mysqli_real_escape_string($conn, $data['priority']);
    $description = mysqli_real_escape_string($conn, $data['description']);

    // SQL query to insert task, with assigned_to as userId
    $sql = "INSERT INTO task (user_id, assigned_to, title, due_date, status, priority, description) VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters, user_id and assigned_to will both use $userId
        $stmt->bind_param("iisssss", $userId, $userId, $title, $due_date, $status, $priority, $description);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save task']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
}

$conn->close();
?>

