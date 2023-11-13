<?php
include "dbconnect.php";

error_log('Received taskId: ' . $_POST['taskId']);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['taskId'])) {
    $taskId = $_POST['taskId'];

    // Perform the database update
    $query = "UPDATE active_task SET completion_state = TRUE WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $taskId);
        $stmt->execute();
        $stmt->close();
        echo "success";
    } else {
        // Database error
        echo "error";
    }
}

$conn->close();
?>
