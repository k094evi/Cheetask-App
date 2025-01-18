<?php
include('Connect.php');

if (isset($_GET['id'])) {
    $taskId = intval($_GET['id']);

    $query = "DELETE FROM trash WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $taskId);
    $stmt->execute();

    header("Location: Trash.php");
    exit();
}

header("Location: Trash.php");
exit();
?>
