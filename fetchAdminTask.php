<?php
session_start();
include('Connect.php');

if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(["message" => "Unauthorized"]);
    exit();
}

$userId = $_SESSION['id'];

// If the user is an admin, fetch all tasks
$query = "SELECT t.id, t.title, t.description, t.priority, t.status, t.due_date, t.assigned_to, u.username AS assigned_to_name FROM task t LEFT JOIN users u ON t.assigned_to = u.id";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$tasks = [];
while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}

echo json_encode(["tasks" => $tasks]);
?>
