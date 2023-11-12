<?php
include "dbconnect.php";

if (!isset($_SESSION['user_id'])) { 
    // Handle unauthorized access as needed
    header("HTTP/1.1 401 Unauthorized");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'])) {
    $task = $_POST['task'];

    // Input validation (add more as needed)
    if (empty($task)) { 
        echo "error";
    } else {
        // Perform the database insert
        $sql = "INSERT INTO active_task (task, User_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("ss", $task, $user_id);
            $stmt->execute(); // Execute the statement before closing
            $stmt->close();
            echo "success";
        } else {
            // Database error
            echo "error";
        }
    }
}

$conn->close();
?>
