<?php
session_start();
include('Connect.php');

if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(["message" => "Unauthorized"]);
    exit();
}

$userId = $_SESSION['id'];

$query = "SELECT id, title, description, priority, status, due_date FROM task WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

$task = [];
while ($row = $result->fetch_assoc()) {
    $task[] = $row;
}

echo json_encode(["task" => $task]);
?>
